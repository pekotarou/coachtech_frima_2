<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

// 修正: ProfileRequest 1つに統一
use App\Http\Requests\ProfileRequest;



class ProfileController extends Controller
{
    // 初回プロフィール設定画面
    public function create()
    {
        return view('profile.create');
    }

    // 初回プロフィール保存
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

        // 初回プロフィール設定後はトップページへ
        return redirect('/');
    }

    // プロフィール画面
    public function show()
    {
        $user = Auth::user();

        return view('profile.show', compact('user'));
    }

    // プロフィール設定・編集画面
    public function edit()
    {
        $profile = Auth::user()->profile;

        // 修正: プロフィール未登録なら初回設定画面
        if (!$profile) {
            return view('profile.create');
        }

        // 修正: プロフィール登録済みなら編集画面
        return view('profile.edit', compact('profile'));
    }

    // プロフィール更新
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $data = [
            'name' => $request->name,
            'zip_code' => $request->zip_code,
            'residence' => $request->residence,
            'building' => $request->building,
        ];

        // 修正: 編集時は画像が選択された場合だけ更新
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('profiles', 'public');
        }

        // 修正: プロフィールがあれば更新、なければ作成
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );
        // 編集後はマイページへ
        return redirect('/mypage');
    }
}



