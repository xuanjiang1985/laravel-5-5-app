<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminChangePassword extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '新密码' => 'required|confirmed|min:6',
            '旧密码' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute 不能为空；',
            'min'  => ':attribute 长度至少6个字符；',
            'confirmed' => ':attribute 两次输入不相同；'
        ];
    }


}
