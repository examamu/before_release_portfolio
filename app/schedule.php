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

    //start_timeの秒数を表示前に削除
    public function cut_seconds($str)
    {
        $word_count = 3;
        return substr($str, 0 , strlen($str) - $word_count );
    }

    private function cut_minutes_seconds($str)
    {
        $word_count = 6;
        return substr($str, 0 , strlen($str) - $word_count );
    }

    public function get_schedule_data()
    {
        $weekly_array = $this->calendar();

        //認証user_idを取得
        $login_user_data = Auth::user();

        //認証user_idを利用しログインしているstaff情報取得
        $user_data = \App\Staff::where('user_id', $login_user_data->id)->first();

        //施設情報取得
        $facility_data = \App\Facility::find($user_data->facility_id)->first();

        //今日のスケジュール一覧を表示
        if(config('const.CURRENT_TIME') >=  $facility_data->closing_hours)
        {
            $today_schedules = \App\Schedule::where('date',config('const.TODAY'))->where('facility_id', $user_data->facility_id)->limit(10)->offset(1)->orderBy('start_time','asc')->get();
        }else
        {
            $today_schedules = \App\Schedule::where('date',date('Y-m-d',strtotime('+1 day')))->where('facility_id', $user_data->facility_id)->limit(10)->offset(1)->orderBy('start_time','asc')->get();
        }
        //直近のスケジュールを１件表示
        $next_schedule = \App\Schedule::where('start_time', '>=', config('const.CURRENT_TIME'))->orderBy('start_time','asc')->first();

        //終了スケジュール取得
        $today_finish_schedules = \App\Schedule_history::where('date',config('const.TODAY'))->where('facility_id', $user_data->facility_id)->get();

        //施設で働いているスタッフ情報取得
        $staffs = \App\Staff::with('user')->where('facility_id',$user_data->facility_id)->get();

        //サービス一覧取得
        $serviceTypes = \App\ServiceType::All();

        //利用者情報取得
        //施設ごとの利用者を取得
        $customers = \App\Usage_situation::with('customer')->where('facility_id',$user_data->facility_id)->get();

        //利用している利用者を取得
        $active_customers = \App\Customer::where('status',1)->get();

        //営業時間取得
        $opening_hours = $this->cut_minutes_seconds($facility_data->opening_hours);
        $closing_hours = $this->cut_minutes_seconds($facility_data->closing_hours);

        $var_array = array(
            'customers' => $this->customers($customers),
            'active_customers' => $active_customers,
            'staffs' => $staffs,
            'serviceTypes' => $serviceTypes,
            'weekly_array' => $weekly_array,
            'times' => $this->times($opening_hours,$closing_hours),
            'facility_data' => $facility_data,
            'today_schedules' => $today_schedules,
            'next_schedule' => $next_schedule,
            'today_finish_schedules'=>$today_finish_schedules,
        );

        return $var_array;
    }


    private function times($opening_hours,$closing_hours)
    {
        for ($i = $opening_hours; $i <= $closing_hours-1; $i++)
        {
            for ($j = 0; $j <= 30; $j += 30) {
                $times[] = sprintf("%02d:%02d\n", $i, $j);
            }
        }
        return $times;
    }

    private function customers($customers)
    {
        foreach($customers as $customer){
            //取得時になくなった0を取得し直し
            $int = sprintf('%07d',$customer->date_of_use);
            
            //一つずつ配列に格納
            $int_array = str_split($int);

            $i = 0;
            foreach ( $int_array as $value ){
            //$customer->data_of_useの曜日の項目の桁が0なら
                if($value === '1'){
                    $int_array[$i] = config('const.WEEK')[$i];
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
        return $customers;
    }


    public function calendar()
    {
        $y = date('Y');
        $m = date('m');
        $d = date('d');
        $w = date('w');

        if($w > 0){
            $num = -$w;
        }else{
            $num = $w;
        }
        $d = date('d') + $num;

        for($i = $d; $i < $d+7; $i++){
            if($i <= 0){
                $weekly_array[] = date('Y-m-d', mktime(0, 0, 0, $m, 0, $y )) + $i;

            }elseif(checkdate( $m, $i, $y ) === FALSE){
                $y--;
                $m--;
                $weekly_array[] = $y.'-'.$m.'-'.$i - date('t');

            }else{
                $weekly_array[] = $y.'-'.$m.'-'.$i;
            }
        } 
        return $weekly_array;
    }
}
