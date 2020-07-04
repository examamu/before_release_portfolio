<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Schedule_history extends Model
{   

    public function customer()
    {
        return $this->belongsTo('App\customer');
    }

    public static function today_schedule_histories($facility_id)
    {
        $today_schedule_histories = self::with('customer')->where('date',config('const.TODAY'))->where('facility_id', $facility_id)->get();

        return $today_schedule_histories;
    }
    
    public function insert($finish_schedules)
    {
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
    }
}