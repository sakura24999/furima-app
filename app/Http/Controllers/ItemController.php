<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $query = Item::with(['user', 'categories'])->where('status', 'available')->latest();

        if ($category) {
            $query->whereHas('categories', function ($q) use ($category) {
                $q->where('categories.id', $category);
            });
        }

        $items = $query->paginate(20);

        $categories = Category::all();

        return view('items.index', compact('items', 'categories', 'search', 'category'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('items.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1',
            'condition' => 'required|string|in:new,like_new,good,fair,poor',
            'image' => 'required|image|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        $imagePath = $request->file('image')->store('items', 'public');

        $item = new Item();
        $item->user_id = Auth::id();
        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->condition = $request->condition;
        $item->image = $imagePath;
        $item->status = 'available';
        $item->save();

        $item->categories()->attach($request->categories);

        return redirect()->route('items.show', $item)->with('success', '商品を出品しました!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item->load(['user', 'categories', 'comments.user']);

        $likesCount = $item->likes()->count();

        $isLiked = false;
        if (Auth::check()) {
            $isLiked = $item->likes()->where('user_id', Auth::id())->exists();
        }

        return view('items.show', compact('item', 'likesCount', 'isLiked'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $this->authorize('update', $item);

        $categories = Category::all();
        $selectedCategories = $item->categories()->pluck('id')->toArray();

        return view('items.edit', compact('item', 'categories', 'selectedCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:1',
            'condition' => 'required|string|in:new,like_new,good,fair,poor',
            'image' => 'nullable|image|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }

            $imagePath = $request->file('image')->store('items', 'public');
            $item->image = $imagePath;
        }

        $item->name = $request->name;
        $item->description = $request->description;
        $item->price = $request->price;
        $item->condition = $request->condition;
        $item->save();

        $item->categories()->sync($request->categories);

        return redirect()->route('items.show', $item)->with('success', '商品情報を更新しました!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);

        if ($item->purchase) {
            return back()->with('error', '購入済みの商品は削除できません。');
        }

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->likes()->delete();
        $item->comments()->delete();

        $item->delete();

        return redirect()->route('items.index')->with('success', '商品を削除しました!');
    }

    public function myItems()
    {
        $items = Item::where('user_id', Auth::id())->with('categories')->latest()->paginate(10);

        return view('items.my_items', compact('items'));
    }
}
