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

    public function staff()
    {
        return $this->belongsTo('App\staff');
    }

    public static function today_schedule_histories($facility_id)
    {
        $today_schedule_histories = self::with('customer')->where('date',config('const.TODAY'))->where('facility_id', $facility_id)->get();

        return $today_schedule_histories;
    }
    
    public static function insert($finish_schedules)
    {
        foreach($finish_schedules as $finish_schedule)
        {   
            DB::table('schedule_histories')->insert([
                'customer_id' => $finish_schedule['id'],
                'user_id' => $finish_schedule['user_id'],
                'facility_id' => $finish_schedule['facility_id'],
                'service_type_id' => $finish_schedule['service_type_id'],
                'date' => $finish_schedule['date'],
                'start_time' => $finish_schedule['start_time'],
                'description' => $finish_schedule['description'],
            ]);
        }
    }

    public function update_schedule_history($schedule_data)
    {
        $schedule_history_id = \App\Schedule_history::where('schedule_id',$schedule_data['schedule_id'])->first()['id'];
        $update = $this::find($schedule_history_id);
        $update->schedule_id = $schedule_data['schedule_id'];
        $update->customer_id = $schedule_data['customer_id'];
        $update->user_id = $schedule_data['staff_id'];
        $update->facility_id = $schedule_data['facility_id'];
        $update->service_type_id = $schedule_data['service_type_id'];
        $update->date = $schedule_data['date'];
        $update->start_time = $schedule_data['start_time'];

        $update->save();
    }

    public function insert_schedule_history($schedule_data)
    {
        $this->schedule_id = $schedule_data['schedule_id'];
        $this->customer_id = $schedule_data['customer_id'];
        $this->user_id = $schedule_data['staff_id'];
        $this->facility_id = $schedule_data['facility_id'];
        $this->service_type_id = $schedule_data['service_type_id'];
        $this->date = $schedule_data['date'];
        $this->start_time = $schedule_data['start_time'];
        $this->created_at = date('YmdHis');
        $this->update_at = date('YmdHis');

        $this->save();
    }
}