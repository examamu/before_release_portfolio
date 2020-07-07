<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;
use App\Schedule_history;
use App\Staff;
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
    {   
        //認証user_idを取得
        $login_user_data = Auth::user();
        //認証user_idを利用しログインしているstaff情報取得
        $user_data = \App\Staff::where('user_id', $login_user_data->id)->first();
        $finish_schedules = Schedule::finish_schedules($user_data->facility_id); 
        $staff_id = Staff::staff_data($user_data->id);
            DB::transaction(function(){
                //認証user_idを取得
                $login_user_data = Auth::user();
                //認証user_idを利用しログインしているstaff情報取得
                $user_data = \App\Staff::where('user_id', $login_user_data->id)->first();
                $finish_schedules = Schedule::finish_schedules($user_data->facility_id); 
                foreach($finish_schedules as $finish_schedule)
                {   
                    DB::table('schedule_histories')->insert([
                        'schedule_id' => $finish_schedule['id'],
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
        return view('home',[
            'schedules' => Schedule::get_today_schedules($user_data->facility_id),
            'next_schedule' => Schedule::next_schedule($staff_id),
            'finish_schedules' => $finish_schedules,
            'today_finish_schedules' => Schedule_history::today_schedule_histories($user_data->facility_id),
        ]);
    }
}
