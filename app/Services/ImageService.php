<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService
{
    public static function upload($imageFile, $folderName)
    {
        if(is_array($imageFile)){//配列の場合
            $file = $imageFile['image']; // 配列なので['key'] で値を取得
        }else{
            $file = $imageFile;//imageファイルをファイルとして保存
        }
        // Storage::putFile('public/shops', $imageFile);//putFileでファイル名を生成。サーバー側に保存。
        $fileName = uniqid(rand().'_');//ランダムなファイル名にする
        $extension = $file->extension();//拡張子を取得
        $fileNameToStore = $fileName. '.' . $extension;
        $resizedImage = InterventionImage::make($file)->resize(1920, 1080)->encode();
        Storage::put('public/' . $folderName . '/' .$fileNameToStore, $resizedImage );

        return $fileNameToStore;
    }
}