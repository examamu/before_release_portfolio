<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function schedules()
    {
        return $this->hasMany('App\schedule');
    }

    public function usage_situations()
    {
        return  $this->hasMany('App\Usage_situation');
    }

    public static function active_customer($facility_id)
    {
        $active_customers = \App\Usage_situation::with('customer_status_1')->where('facility_id', $facility_id)->get();

        return $active_customers;
    }

    public static function customers($facility_id)
    {
        $customers_usage_situation = \App\Usage_situation::with('customer')->where('facility_id',$facility_id)->get();
        $customers = self::change_caretype($customers_usage_situation);

        if(isset($customers)){
            return $customers;
        }else{
            return array('利用者はまだ登録されていません');
        }
        
    }

    public static function change_caretype($customers)
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

    public function insert_customer_data($insert_customer_data){

        $this->name = $insert_customer_data['post_name'];
        $this->insurer_number = $insert_customer_data['post_nursing_care_level'];
        $this->nursing_care_level = $insert_customer_data['post_care_level_num'];
        $this->status = 0;

        $this->save();

        return $this->id;
    }
}