<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 商品一覧画面
    public function index(Request $request)
    {
        // 修正: タブ指定がなければおすすめを表示
        $tab = $request->query('tab', 'recommend');

        if ($tab === 'mylist') {
            // 修正: 未ログインの場合、マイリストは何も表示しない
            if (!auth()->check()) {
                $products = collect();
            } else {
                // 修正: いいね機能作成前なので、ログイン後も一旦空にする
                $products = collect();
            }
        } else {
            // 修正: おすすめ商品一覧
            $products = Product::latest()->get();
        }

        return view('products.index', compact('products', 'tab'));
    }

    //商品詳細画面
    public function show(Product $product)
    {
        //関連データも一緒に取得
        $product->load(['categories', 'brand', 'status', 'user']);

        return view('products.show', compact('product'));
    }
}