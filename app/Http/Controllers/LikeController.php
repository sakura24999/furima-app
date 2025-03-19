<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $likedItems = Auth::user()->likedItems()->with(['user', 'categories'])->latest('likes.created_at')->paginate(20);

        return view('likes.index', compact('likedItems'));
    }

    public function store(Request $request, Item $item)
    {
        $existing = Like::where('user_id', Auth::id())->where('item_id', $item->id)->first();

        if (!$existing) {
            $like = new Like();
            $like->user_id = Auth::id();
            $like->item_id = $item->id;
            $like->save();
        }

        if ($request->ajax()) {
            $likesCount = $item->likes()->count();

            return response()->json([
                'success' => true,
                'likesCount' => $likesCount
            ]);
        }

        return back();
    }

    public function destroy(Request $request, Item $item)
    {
        $like = Like::where('user_id', Auth::id())->where('item_id', $item->id)->first();

        if ($like) {
            $like->delete();
        }

        if ($request->ajax()) {
            $likesCount = $item->likes()->count();

            return response()->json([
                'success' => true,
                'likesCount' => $likesCount
            ]);
        }
        return back();
    }
}
