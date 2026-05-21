<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//商品一覧画面。未ログインでも表示可能
Route::get('/', [ProductController::class, 'index'])->name('products.index');

//商品詳細画面
Route::get('/item/{product}', [ProductController::class, 'show'])->name('products.show');

//会員登録画面
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

//ログイン画面
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

//ログイン済みユーザーだけ使える動き
Route::middleware(['auth', 'verified'])->group(function () {
    //プロフィール画面
    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile.show');
    //プロフィール編集画面
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //初回プロフィール保存
    Route::post('/mypage/profile', [ProfileController::class, 'store'])->name('profile.store');
    //プロフィール更新
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    //商品出品画面
    Route::get('/sell', [ProductController::class, 'create'])->name('products.create');
    //商品出品保存
    Route::post('/sell', [ProductController::class, 'store'])->name('products.store');
    //商品購入画面
    Route::get('/purchase/{product}', [ProductController::class, 'purchase'])->name('products.purchase');
    //商品いいね登録・解除
    Route::post('/item/{product}/heart', [ProductController::class, 'heart'])->name('products.heart');
    //Stripe Checkoutへ接続
    Route::post('/purchase/{product}', [ProductController::class, 'purchaseStore'])->name('products.purchase.store');
    //Stripe決済成功後
    Route::get('/purchase/{product}/success', [ProductController::class, 'purchaseSuccess'])->name('products.purchase.success');
    //Stripe決済キャンセル後
    Route::get('/purchase/{product}/cancel', [ProductController::class, 'purchaseCancel'])->name('products.purchase.cancel');
    //送付先住所変更画面
    Route::get('/purchase/address/{product}', [ProductController::class, 'addressEdit'])->name('purchase.address.edit');
    //送付先住所更新
    Route::post('/purchase/address/{product}', [ProductController::class, 'addressUpdate'])->name('purchase.address.update');
    //コメント投稿
    Route::post('/item/{product}/comment', [ProductController::class, 'comment'])->name('products.comment');

    
});

