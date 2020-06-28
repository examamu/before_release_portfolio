<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   //認証user_idを取得

        function cut_seconds($str)
        {
            $word_count = 3;
            return substr($str, 0 , strlen($str) - $word_count );
        }

        $login_user_data = Auth::user();
        //認証user_idを利用しログインしているstaff情報取得
        $user_data = \App\Staff::where('user_id', $login_user_data->id)->first();
        $finish_schedules = \App\Schedule::where('date',date('Y-m-d'))->where('start_time', '<', date('H:i:s'))->where('facility_id', $user_data->facility_id)->get(); 
            DB::transaction(function(){
                $login_user_data = Auth::user();
                $user_data = \App\Staff::where('user_id', $login_user_data->id)->first();
                $finish_schedules = \App\Schedule::where('date',date('Y-m-d'))->where('start_time', '<', date('H:i:s'))->where('facility_id', $user_data->facility_id)->get();
                foreach($finish_schedules as $finish_schedule)
                {   
                    DB::table('schedule_histories')->insert([
                        'customer_id' => $finish_schedule['customer_id'],
                        'user_id' => $finish_schedule['user_id'],
                        'facility_id' => $finish_schedule['facility_id'],
                        'service_type_id' => $finish_schedule['service_type_id'],
                        'date' => $finish_schedule['date'],
                        'start_time' => $finish_schedule['start_time'],
                        'description' => $finish_schedule['description'],
                    ]);
                }
                DB::table('schedules')->where('date',date('Y-m-d'))->
                where('start_time', '<', date('H:i:s'))->
                where('facility_id', $user_data->facility_id)->
                delete();
            });
        
        //scheduleモデルを使用してスケジュール一覧を取得
        $schedule_model = new Schedule;
        $data = $schedule_model->get_schedule_data();

        return view('home',[
            'schedules' => $data['today_schedules'],
            'next_schedule' => $data['next_schedule'],
            'finish_schedules' => $finish_schedules,
            'today_finish_schedules' => $data['today_finish_schedules'],
        ]);
    }
}
