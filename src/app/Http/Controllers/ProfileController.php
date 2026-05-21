<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

//ProfileRequest 1つに統一
use App\Http\Requests\ProfileRequest;
use App\Models\Product;
use App\Models\Order;



class ProfileController extends Controller
{
    //初回プロフィール設定画面
    public function create()
    {
        return view('profile.create');
    }

    //初回プロフィール保存
    public function store(ProfileRequest $request)
    {
        $user = Auth::user();

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('profiles', 'public');
        }

        $user->profile()->create([
            'name' => $request->name,
            'zip_code' => $request->zip_code,
            'residence' => $request->residence,
            'building' => $request->building,
            'image' => $imagePath,
        ]);

        //初回プロフィール設定後はトップページへ
        return redirect('/');
    }

    //プロフィール画面
    public function show()
    {
        $user = Auth::user();

        //マイページのタブ判定
        // /mypage?page=sell → 出品した商品
        // /mypage?page=buy  → 購入した商品
        $page = request()->query('page', 'sell');

        if ($page === 'buy') {
            //ログインユーザーが購入した商品を取得
            $products = Product::whereHas('order', function ($query) use ($user) {
                $query->where('buyer_id', $user->id);
            })
            ->latest()
            ->get();
        } else {
            //ログインユーザーが出品した商品を取得
            $products = Product::where('user_id', $user->id)
                ->latest()
                ->get();
        }

        return view('profile.show', compact('user', 'products', 'page'));
    }



    //プロフィール設定・編集画面
    public function edit()
    {
        $profile = Auth::user()->profile;

        //プロフィール未登録なら初回設定画面
        if (!$profile) {
            return view('profile.create');
        }

        //プロフィール登録済みなら編集画面
        return view('profile.edit', compact('profile'));
    }

    //プロフィール更新
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $data = [
            'name' => $request->name,
            'zip_code' => $request->zip_code,
            'residence' => $request->residence,
            'building' => $request->building,
        ];

        //編集時は画像が選択された場合だけ更新
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('profiles', 'public');
        }

        //プロフィールがあれば更新、なければ作成
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
        //編集後はマイページへ
        return redirect('/mypage');
    }
}



