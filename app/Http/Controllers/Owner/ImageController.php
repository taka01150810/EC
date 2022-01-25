<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

class ImageController extends Controller
{
    //ログイン済みのユーザーのみ表示させるためコンストラクタで下記を設定
    public function __construct()
    {
        $this->middleware('auth:owners');
        $this->middleware(function($request, $next){
            $id = $request->route()->parameter('image');
            if(!is_null($id)){
                $imagesOwnerId = Shop::findOrFail($id)->owner->id;
                $imageId = (int)$imagesOwnerId;
                $ownerId = Auth::id();
            if($imageId !== $ownerId){
                abort(404);
            }
        }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $images = Image::where('owner_id', Auth::id())
        ->orderBy('updated_at', 'desc') // 降順 (小さくなる)
        ->paginate(20);

        return view('owner.images.index',compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        $imageFiles = $request->file('files'); //配列でファイル(複数)を取得。
        if(!is_null($imageFiles)){
            foreach($imageFiles as $imageFile){ // それぞれ処理
                $fileNameToStore = ImageService::upload($imageFile, 'products');
                Image::create([//保存処理
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore
                ]);
            }
        }
        return redirect()
        ->route('owner.shops.index')
        ->with([
            'message' => '画像登録を実施しました',
            'status' => 'info',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
