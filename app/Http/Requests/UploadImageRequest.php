<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//認証されているユーザが使えるかどうか
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => 'image|mimes:jpg,jpeg,png|max:2048',
            'files.*.image' => 'required|image|mimes:jpg,jpeg,png|max:2048',//配列のバリデーション
        ];
    }

    public function messages()
    {
        //エラーメッセージのカスタマイズ
        return[
            'image' => '指定されたファイルが画像ではありません',
            'mines' => '指定された拡張子ではありません',
            'max' => 'ファイルサイズは2MB以内にしてください',
        ];
    }
}
