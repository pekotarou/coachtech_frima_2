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

//会員登録画面
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

//ログイン画面
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

//ログイン済みユーザーだけ使えるプロフィール関連
Route::middleware('auth')->group(function () {
    // プロフィール画面
    Route::get('/mypage', [ProfileController::class, 'show'])->name('profile.show');
    //プロフィール編集画面
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    //初回プロフィール保存
    Route::post('/mypage/profile', [ProfileController::class, 'store'])->name('profile.store');
    //プロフィール更新
    Route::patch('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

});

