<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Models\Order;
use App\Http\Requests\AddressRequest;
use App\Models\Heart;
use App\Http\Requests\CommentRequest;
use App\Models\Comment;


class ProductController extends Controller
{
    //商品一覧画面
    public function index(Request $request)
    {
        //タブ指定がなければおすすめを表示
        $tab = $request->query('tab', 'recommend');

        //検索キーワードを取得
        $keyword = $request->query('keyword');

        if($tab === 'mylist') {
            //未ログインの場合、マイリストは何も表示しない
            if (!auth()->check()) {
                $products = collect();
            } else {
                //ログインユーザーがいいねした商品を取得
                $query = Product::whereHas('hearts', function ($query) {
                    $query->where('user_id', auth()->id());
                });

                //検索キーワードがあれば、マイリスト内でも商品名検索
                if (!empty($keyword)) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                }

                $products = $query->latest()->get();
            }
        } else {
            //おすすめ商品一覧
            $query = Product::query();

            //ログイン中は自分が出品した商品をおすすめ一覧から除外
            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }

            //キーワードが入力されている場合は商品名で検索
            if (!empty($keyword)) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }

            $products = $query->latest()->get();
        }

        return view('products.index', compact('products', 'tab', 'keyword'));
    }





    //商品詳細画面
    public function show(Product $product)
    {
        //商品詳細で使う関連データを取得
        $product->load([
            'categories', 
            'brand', 
            'status', 
            'user',
            'hearts',
            'comments.user.profile',            
        ]);

        //いいね数を取得
        $heartCount = $product->hearts()->count();

        //コメント数を取得
        $commentCount = $product->comments()->count();

        //ログインユーザーがこの商品にいいねしているか判定
        $isHearted = false;

        if (auth()->check()) {
            $isHearted = $product->hearts()
                ->where('user_id', auth()->id())
                ->exists();
        }






        return view('products.show', compact('product', 'heartCount', 'commentCount','isHearted'));
    }



        // 商品出品画面
    public function create()
    {
        // 修正: 出品画面で使うカテゴリーと商品の状態を取得
        $categories = Category::all();
        $statuses = Status::all();

        return view('products.sell', compact('categories', 'statuses'));
    }

    // 商品出品保存
    public function store(ExhibitionRequest $request)
    {
        // 修正: 商品画像を storage/app/public/products に保存
        $imagePath = $request->file('image')->store('products', 'public');

        // 修正: ブランド名が空なら「なし」として保存する
        $brandName = $request->brand_name ?: 'なし';

        // 修正: brandsテーブルから取得。なければ作成
        $brand = Brand::firstOrCreate([
            'name' => $brandName,
        ]);

        // 修正: productsテーブルに商品を保存
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'status_id' => $request->status_id,
            'brand_id' => $brand->id,
            'user_id' => Auth::id(),
            'order_id' => null,
        ]);

        // 修正: product_categoryテーブルに複数カテゴリーを保存
        $product->categories()->attach($request->category_ids);

        // 修正: 出品後は商品一覧画面へ
        return redirect()->route('products.index');
    }

    // 商品購入画面
    public function purchase(Product $product)
    {
        $user = Auth::user();

        // 修正: 商品情報にブランド・状態・出品者も読み込む
        $product->load(['brand', 'status', 'user']);

        // 修正: sessionに保存された送付先住所を取得
        $address = session('purchase_address.' . $product->id);

        // 修正: sessionに住所がなければ、プロフィール住所を使う
        if (!$address && $user->profile) {
            $address = [
                'zip_code' => $user->profile->zip_code,
                'residence' => $user->profile->residence,
                'building' => $user->profile->building,
            ];
        }
        return view('products.purchase', compact('product', 'user', 'address'));
        //return view('products.purchase', compact('product', 'user'));
    }

    // 商品購入保存
    public function purchaseStore(PurchaseRequest $request, Product $product)
    {
        $user = Auth::user();
        // 修正: プロフィールが未設定なら購入できない
        if (!$user->profile) {
            return redirect()
                ->route('profile.edit')
                ->with('error', '購入前にプロフィールを設定してください。');
        }

        // 修正: すでに購入済みの商品は購入できないようにする
        if ($product->order_id) {
            return redirect()
                ->route('products.show', $product->id)
                ->with('error', 'この商品はすでに購入されています。');
        }

        // 修正: 自分が出品した商品は購入できないようにする
        if ($product->user_id === $user->id) {
            return redirect()
                ->route('products.show', $product->id)
                ->with('error', '自分が出品した商品は購入できません。');
        }


        // 修正: sessionの送付先住所を取得
        $address = session('purchase_address.' . $product->id);

        // 修正: sessionに住所がなければプロフィール住所を使う
        if (!$address && $user->profile) {
            $address = [
                'zip_code' => $user->profile->zip_code,
                'residence' => $user->profile->residence,
                'building' => $user->profile->building,
            ];
        }

        // 修正: 住所がない場合は購入できない
        if (!$address) {
            return redirect()
                ->route('purchase.address.edit', $product->id)
                ->withErrors([
                    'zip_code' => '送付先住所を入力してください。',
                ]);
        }


        // 修正: ordersテーブルに購入情報を保存
        $order = Order::create([
            'product_id' => $product->id,
            'buyer_id' => $user->id,
            'payment' => $request->payment,
            'seller_id' => $product->user_id,
            //購入時点の送付先住所をordersに保存
            'zip_code' => $address['zip_code'],
            'residence' => $address['residence'],
            'building' => $address['building'],
        ]);

        // 修正: products.order_id に購入情報IDを保存
        $product->update([
            'order_id' => $order->id,
        ]);

        // 追加: 購入完了後、この商品の一時住所を削除
        session()->forget('purchase_address.' . $product->id);
        // 修正: 購入後は商品一覧へ戻る
        return redirect()->route('products.index');
    }

    // 送付先住所変更画面
    public function addressEdit(Product $product)
    {
        $user = Auth::user();

        return view('products.address', compact('product', 'user'));
    }

    // 送付先住所更新
    public function addressUpdate(AddressRequest $request, Product $product)
    {
        // 修正: 購入前の送付先住所をsessionに保存
        session([
            'purchase_address.' . $product->id => [
                'zip_code' => $request->zip_code,
                'residence' => $request->residence,
                'building' => $request->building,
            ],
        ]);

        // 修正: 購入画面へ戻る
        return redirect()->route('products.purchase', $product->id);
    }


    //商品いいね登録・解除
    public function heart(Product $product)
    {
        $user = Auth::user();

        //すでにいいねしているか確認
        $heart = Heart::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($heart) {
            //いいね済みなら削除
            $heart->delete();
        } else {
            //未いいねなら登録
            Heart::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
        }

        return redirect()->route('products.show', $product->id);
    }

    //コメント投稿
    public function comment(CommentRequest $request, Product $product)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'comment' => $request->comment,
        ]);

        return redirect()->route('products.show', $product->id);
    }




}