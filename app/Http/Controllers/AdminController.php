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
        $serviceTypes = \App\ServiceTypes::All();
        //利用者情報取得
        $customers = \App\Customers::where('status',1)->get();
        foreach($customers as $customer){
            if($customer->care_type === 1){
                $customer->care_type = '要介護';
            }else{
                $customer->care_type = '要支援';
            }
        }


        function cut_minutes_seconds($str){
        $word_count = 6;
            return substr($str, 0 , strlen($str) - $word_count );
        }
        $opening_hours = cut_minutes_seconds($facility_data->opening_hours);
        $closing_hours = cut_minutes_seconds($facility_data->closing_hours);;

        for ($i = $opening_hours; $i <= $closing_hours-1; $i++) {
            for ($j = 0; $j <= 30; $j += 30) {
                $times[] = sprintf("%02d:%02d\n", $i, $j);
            }
        }


        
        return view('admin',[
            'customers' => $customers,
            'staffs' => $staffs,
            'serviceTypes' => $serviceTypes,
            'week' => $week,
            'weekly_array' => $weekly_array,
            'times' => $times,
            'facility_data' => $facility_data,
        ]);
    }
}