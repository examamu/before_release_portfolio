<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class schedule extends Model
{   
    protected $table = 'schedules';

    public function customer()
    {
       return $this->belongsTo('App\customers');
    }

    //start_timeの秒数を表示前に削除
    public function cut_seconds($str)
    {
        $word_count = 3;
        return substr($str, 0 , strlen($str) - $word_count );
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
