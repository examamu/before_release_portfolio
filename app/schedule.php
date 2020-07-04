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

    public static function get_exist_data($facility_id)
    {   
        //営業時間の取得
        $facility_business_hour = Calendar::times($facility_id);
        
        //日付の取得(1週間分)
        $weekly_calendar = Calendar::weekly_calendar();
        $i = 0;
        foreach($weekly_calendar as $date)
        {
            foreach($facility_business_hour as $hour)
            {
                //日付が今日より先かつ時間が今より後の場合
                if($date < date('Y-m-d') && $hour < date('H:i:s')){
                    continue;
                }
                //スケジュールテーブルから日付と時間で絞り込んで取得
                // $schedule_exist = \App\Schedule::where('date',$date)->where('start_time',$hour)->get();
                $get_schedule_array[] = $i;
                //テーブルからデータを取得していたら
                //予定が終了している場合
                // }else
                // {
                //     //スケジュールヒストリーテーブルから日付と時間で絞り込んで取得
                //     $finish_schedule_exist = Schedule_history::where('date',$date)->where('start_time',trim($hour).':00')->get();
                //     if(isset($finish_schedule_exist) === TRUE)
                //     {
                //         $get_schedule_array[$date.$hour] = $finish_schedule_exist;
                //     }
                $i ++;
            }
        }
        return $get_schedule_array;
    }
}
