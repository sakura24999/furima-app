<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function confirm(Item $item)
    {
        if ($item->user_id === Auth::id()) {
            return redirect()->route('items.show', $item)->with('error', '自分の出品した商品は購入できません。');
        }

        if ($item->status !== 'available') {
            return redirect()->route('items.show', $item)->with('error', 'この商品は既に購入されています。');
        }

        $user = Auth::user();

        return view('purchases.confirm', compact('item', 'user'));
    }

    public function store(Request $request, Item $item)
    {
        if ($item->user_id === Auth::id()) {
            return redirect()->route('items.show', $item)->with('error', '自分の出品した商品は購入できません。');
        }

        if ($item->status !== 'available') {
            return redirect()->route('items.show', $item)->with('error', 'この商品は既に購入されています。');
        }

        $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|size:7',
            'shipping_address' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:15',
            'payment_method' => 'required|string|in:credit_card, bank_transfer,convenience_store',
        ]);

        DB::beginTransaction();

        try {
            $item->status = 'sold';
            $item->save();

            $purchase = new Purchase();
            $purchase->user_id = Auth::id();
            $purchase->item_id = $item->id;
            $purchase->shipping_name = $request->shipping_name;
            $purchase->shipping_name = $request->shipping_name;
            $purchase->shipping_postal_code = $request->shipping_postal_code;
            $purchase->shipping_address = $request->shipping_address;
            $purchase->shipping_phone = $request->shipping_phone;
            $purchase->payment_method = $request->payment_method;
            $purchase->amount = $item->price;
            $purchase->save();

            DB::commit();

            return redirect()->route('purchases.complete', $purchase)->with('success', '商品の購入が完了しました!');
        } catch (Exception $e) {
            DB::rollBack();

            return back()->with('error', '購入処理中にエラーが発生しました。もう一度お試しください。');
        }
    }

    public function complete(Purchase $purchase)
    {
        $this->authorize('view', $purchase);

        $purchase->load('item.user');

        return view('purchases.complete', compact('purchase'));
    }

    public function index()
    {
        $purchases = Auth::user()->purchases()->with('item')->latest()->paginate(10);

        return view('purchases.index', compact('purchases'));
    }

    public function show(Purchase $purchase)
    {
        $this->authorize('view', $purchase);

        $purchase->load('item.user');

        return view('purchases.show', compact('purchase'));
    }
}
