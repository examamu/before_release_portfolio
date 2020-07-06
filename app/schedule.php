<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class schedule extends Model
{   
    protected $table = 'schedules';

    public function customer()
    {
       return $this->belongsTo('App\customer');
    }

    public function staff()
    {
        return $this->belongsTo('App\staff');
    }

    public function servicetype()
    {
        return $this->hasOne('App\ServiceType');
    }

    

    public static function get_today_schedules($facility_id)
    {
        $today_schedules = self::where('date',config('const.TODAY'))->where('facility_id', $facility_id)->limit(10)->offset(1)->orderBy('start_time','asc')->get();
        return $today_schedules;
    }

    public static function next_schedule($staff_id)
    {
        $next_schedule = self::where('user_id', $staff_id)->where('date',date('Y-m-d'))->where('start_time', '>=', date('H:i:s'))->orderBy('start_time','asc')->first();
        return $next_schedule;
    }

    public static function finish_schedules($facility_id)
    {
        $finish_schedule = self::where('date','<=',date('Y-m-d'))->where('start_time', '<', date('H:i:s'))->where('facility_id', $facility_id)->get(); 
        return $finish_schedule;
    }


    
    private static function get_schedule_history_data($date,$hour){
        $login_user_data = Auth::user();
        $facility_id = Staff::staff_data($login_user_data)->facility_id;
        $schedule_history_data = \App\Schedule_history::where('facility_id', $facility_id)->where('date',$date)->where('start_time',$hour)->first();
        if(isset($schedule_history_data) === TRUE){
            $service_type_data = \App\ServiceType::find($schedule_history_data['service_type_id']);
            $user_data = \App\User::find($schedule_history_data['user_id']);
            $customer_data = \App\Customer::find($schedule_history_data['customer_id']);
            $get_weekly_schedule = [
                'service_type_data' => $service_type_data,
                'user_data' => $user_data,
                'customer_data' => $customer_data,
            ];
            return $get_weekly_schedule;
        }
    }

    private static function get_schedule_data($date,$hour){
        $login_user_data = Auth::user();
        $facility_id = Staff::staff_data($login_user_data)->facility_id;
        $schedule_data = \App\Schedule::where('facility_id', $facility_id)->where('date',$date)->where('start_time',$hour)->first();
        if(isset($schedule_data) === TRUE){
            $service_type_data = \App\ServiceType::find($schedule_data['service_type_id']);
            $user_data = \App\User::find($schedule_data['user_id']);
            $customer_data = \App\Customer::find($schedule_data['customer_id']);
            $get_weekly_schedule = [
                'service_type_data' => $service_type_data,
                'user_data' => $user_data,
                'customer_data' => $customer_data,
            ];
            return $get_weekly_schedule;
        }
    }

    public static function search_schedule(){
        $login_user_data = Auth::user();
        $facility_id = Staff::staff_data($login_user_data)->facility_id;
        $weekly_array = Calendar::weekly_calendar();
        $times = Calendar::times($facility_id);
        //1週間のループ
        for ($i = 0; $i <= 6; $i++)
        {
            //日付の取得
            $date = $weekly_array[$i];
            //営業時間のループ
            foreach($times as $time)
            {
                //既存の予定の取得
                if($date.$time >= date('Y-m-dH:i:s')){
                    $get_weekly_schedule = self::get_schedule_data($date,$time);
                }else{
                    $get_weekly_schedule = self::get_schedule_history_data($date,$time);
                }
                $schedule[$date][$time] = $get_weekly_schedule;
            }
        }
        return $schedule;
    }
}
