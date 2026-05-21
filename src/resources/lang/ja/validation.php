<?php

return [
    //基本メッセージ
    'required' => ':attributeを入力してください',
    'email' => ':attributeはメール形式で入力してください',

    //項目名
    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
    ],

    //個別メッセージ
    'custom' => [
        'email' => [
            'required' => 'メールアドレスを入力してください',
            'email' => 'メールアドレスはメール形式で入力してください',
        ],
        'password' => [
            'required' => 'パスワードを入力してください',
        ],
    ],
];