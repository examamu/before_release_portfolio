<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\CustomerRequest;
use App\Customer;
use App\Usage_situation;

class CustomerController extends Controller
{
    public static function index(){

        return view('customer',[
            'admin' => 1,
            'week' => config('const.WEEK'),
            'msg' =>'フォームを入力してください',
        ]);
    }

    public function create(CustomerRequest $request){



        $customer_name = $request->input('customer_name');
        $post_week =  $request->input('post_week');
        $nursing_care_level = $request->input('nursing_care_level');
        $care_level_num = $request->input('care_level_num');
        $customer_model = new Customer;
        $usage_situation_model = new Usage_situation;

        $insert_customer_data = [
            'post_name' => $customer_name,
            'post_nursing_care_level' => $nursing_care_level,
            'post_care_level_num' => $care_level_num,
        ];
        DB::beginTransaction();
        try{
            $customer_id = $customer_model->insert_customer_data($insert_customer_data);
            $usage_situation_model->insert_usage_situations_data($post_week,$customer_id);
            DB::commit();
        }catch(\Exception $e){
            $err_msg[] = '利用者の登録に失敗しました'.$e->getMessage();
            DB::rollBack();
        }
        
        return view('customer',[
            'admin' => 1,
            'week' => config('const.WEEK'),
            'msg' =>'登録完了しました！',
        ]);
    }
}