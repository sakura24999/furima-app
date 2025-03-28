<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use GuzzleHttp\Psr7\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', [ItemController::class, 'index'])->name('home');

Route::resource('items', ItemController::class)->only(['index', 'show']);

Route::middleware(['auth'])->group(function () {

    // メール認証関連のルート
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return redirect('/')->with('verified', true);
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', '確認リンクを送信しました');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // 商品関連
    Route::resource('items', ItemController::class)->except(['index', 'show']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/my-items', [ItemController::class, 'myItems'])->name('items.myItems');

    // いいね関連
    Route::resource('items.likes', LikeController::class)->only(['store', 'destroy']);

    Route::get('/likes', [LikeController::class, 'index'])->name('likes.index');

    // コメント関連
    Route::resource('items.comments', CommentController::class)->only(['store']);

    Route::resource('comments', CommentController::class)->only(['destroy']);

    // プロフィール関連
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/profile/password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');

    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

    // 購入関連
    Route::get('/items/{item}/purchase', [PurchaseController::class, 'confirm'])->name('purchases.confirm');

    Route::post('items/{item}/purchase', [PurchaseController::class, 'store'])->name('purchases.store');

    Route::get('purchases/{purchase}/complete', [PurchaseController::class, 'complete'])->name('purchases.complete');

    Route::resource('purchases', PurchaseController::class)->only(['index', 'show']);
});
