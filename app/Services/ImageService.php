<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use InterventionImage;

class ImageService
{
    public static function upload($imageFile, $folderName)
    {
        // Storage::putFile('public/shops', $imageFile);//putFileでファイル名を生成。サーバー側に保存。
        $fileName = uniqid(rand().'_');//ランダムなファイル名にする
        $extension = $imageFile->extension();//拡張子を取得
        $fileNameToStore = $fileName. '.' . $extension;
        $resizedImage = InterventionImage::make($imageFile)->resize(1920, 1080)->encode();
        Storage::put('public/' . $folderName . '/' .$fileNameToStore, $resizedImage );

        return $fileNameToStore;
    }
}