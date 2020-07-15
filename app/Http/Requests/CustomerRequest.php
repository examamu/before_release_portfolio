<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->path() == 'customer'){
            return true;
        }else{
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
        $return_value = [
            'customer_name' => 'required',//利用者名
            'nursing_care_level' => 'required|numeric',
        ];

        if($this->nursing_care_level == 1)//要介護なら
        {
            $return_value['care_level_num'] = 'required|numeric|between:1,5';//介護度の数字  
        }else{//要支援なら
            $return_value['care_level_num'] = 'required|numeric|between:1,2';//介護度の数字  
        }

        return $return_value;
    }

    public function messages()
    {
        return [
            'customer_name.required' => '名前は必ず入力してください',
            'nursing_care_level.required' => '要介護か要支援かをお選びください',
            'nursing_care_level.numeric' => '不正な操作です', 
            'care_level_num.between' => '要支援は1~2までの中からお選びください',
            'care_level_num.numeric' => '不正な操作です',
            'care_level_num.required' => '介護度の数字を入力してください',
        ];
    }
}
