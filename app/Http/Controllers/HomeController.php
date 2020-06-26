<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule_history;
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
        $login_user_data = Auth::user();
        //認証user_idを利用しログインしているstaff情報取得
        $user_data = \App\Staff::where('user_id', $login_user_data->id)->first();
        $schedule_historys_model = new Schedule_history;
        
        $finish_schedules = \App\Schedule::where('date',config('const.TODAY'))->where('start_time', '<', config('const.CURRENT_TIME'))->where('facility_id', $user_data->facility_id);

        if( isset($finish_schedules) === TRUE )
        {   
            DB::beginTransaction();
            try
            {
                foreach($finish_schedules as $data)
                {
                    $schedule_historys_model->customer_id = $data['customer_id'];
                    $schedule_historys_model->user_id = $data['user_id'];
                    $schedule_historys_model->facility_id = $data['facility_id'];
                    $schedule_historys_model->service_type_id = $data['service_type_id'];
                    $schedule_historys_model->date = $data['date'];
                    $schedule_historys_model->start_time = $data['start_time'];
                    $schedule_historys_model->description = $data['description'];

                    $schedule_historys_model->save();
                    
                }
                $finish_schedules->delete();

                DB::commit();
            } catch(\Exception $e)
            {
                DB::rollback();
                throw $e;
            }
        }
        
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
