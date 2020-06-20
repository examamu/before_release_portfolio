<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;
use App\User;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {   

        $schedule_model = new Schedule();
        $weekly_array = $schedule_model->calendar();
        $week = ['日','月','火','水','木','金','土'];

        //認証user_idを取得
        $login_user_data = Auth::user();
        //認証user_idを利用しログインしているstaff情報取得
        $login_user_data = \App\Staff::where('user_id', $login_user_data->id)->first();
        //施設情報取得
        $facility_data = \App\Facility::find($login_user_data->facility_id)->first();
        //施設で働いているスタッフ情報取得
        $staffs = \App\Staff::with('user')->where('facility_id',$login_user_data->facility_id)->get();
        //サービス一覧取得
        $serviceTypes = \App\ServiceType::All();
        //利用者情報取得
        //
        $active_customers = \App\Customer::where('status',1)->get();
        $customers = \App\Usage_situation::with('customer')->where('facility_id',$login_user_data->facility_id)->get();
        
        foreach($customers as $customer){
            //取得時になくなった0を取得し直し
            $int = sprintf('%07d',$customer->date_of_use);
            
            //一つずつ配列に格納
            $int_array = str_split($int);

            $i = 0;
            foreach ( $int_array as $value ){
            //$customer->data_of_useの曜日の項目の桁が0なら
                if($value === '1'){
                    $int_array[$i] = $week[$i];
                }else{
                    //削除
                    unset($int_array[$i]);
                }
                $i++;
            }
            $customer->date_of_use = implode(',', $int_array);
            //利用者の介護度を表示
            if($customer->customer->care_type === 1){
                $customer->customer->care_type = '要介護';
            }else{
                $customer->customer->care_type = '要支援';
            }
        }

        //時間の表示を調整
        function cut_minutes_seconds($str){
        $word_count = 6;
            return substr($str, 0 , strlen($str) - $word_count );
        }
        $opening_hours = cut_minutes_seconds($facility_data->opening_hours);
        $closing_hours = cut_minutes_seconds($facility_data->closing_hours);

        for ($i = $opening_hours; $i <= $closing_hours-1; $i++) {
            for ($j = 0; $j <= 30; $j += 30) {
                $times[] = sprintf("%02d:%02d\n", $i, $j);
            }
        }


        
        return view('admin',[
            'customers' => $customers,
            'active_customers' => $active_customers,
            'staffs' => $staffs,
            'serviceTypes' => $serviceTypes,
            'week' => $week,
            'weekly_array' => $weekly_array,
            'times' => $times,
            'facility_data' => $facility_data,
        ]);
    }
}