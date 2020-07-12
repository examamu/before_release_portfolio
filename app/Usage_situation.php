<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Usage_situation extends Model
{
    public function customer()
    {
        return  $this->belongsTo('App\Customer');
    }

    public function customer_status_1()
    {
        return $this->customer()->where('status', 1);
    }

    public  function insert_usage_situations_data($week_array,$insert_customer_id)
    {   
        $user_id = Auth::id();

        $date_of_use = self::use_week($week_array);
        $customer_id = $insert_customer_id;
        $facility_id = Staff::where('user_id',$user_id)->first()['facility_id'];

        $this->customer_id = $customer_id;
        $this->facility_id = $facility_id;
        $this->date_of_use = $date_of_use;

        $this->save();
    }

    public static function use_week($week_array)
    {
        $week = config('const.WEEK');
            for($i = 0; $i<7; $i++){
                $j = 0;
                foreach($week_array as $date){
                    if($date == $i){
                        $use_week[] = 1;
                        $j++;
                        continue;
                    }
                    continue;
                }
                if($j === 0){
                    $use_week[] = 0;
                    continue;
                }
            }
            $use_week = implode($use_week);
        return $use_week;
    }
}
