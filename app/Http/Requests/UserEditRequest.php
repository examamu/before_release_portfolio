<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->path() == 'user')
        {
            return true;
        }else
        {
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'update_name' => 'required',
            'update_facility' => 'required',
            'update_email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'update_name.required' => '名前は必ず入力してください',
            'update_facility.required' => '施設名は必ず入れてください',
            'update_email.required' => 'メールアドレスは必ず入れてください',
            'update_email.email' => 'メールアドレスの形式で入力してください',
        ];
    }
}
