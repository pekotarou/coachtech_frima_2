<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;


class ProfileController extends Controller
{
    // 初回プロフィール設定画面
    public function create()
    {
        return view('profile.create');
    }

     // プロフィール保存
    public function store(Request $request)
    {
        //
    }

    // プロフィール画面
    public function show()
    {
        $user = Auth::user();

        return view('profile.show', compact('user'));
    }


}
