<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{   
    public static function weekly_calendar()
    {
        $y = date('Y');
        $m = date('m');
        $w = date('w');//日が0として土が6の数字をふる
        $t = date('t');

        //週始まりの日付を取得
        $d = date('d') - $w;

        //週始まり起算で日付7日間取得
        for($i = $d; $i < $d+7; $i++){
            //取得した日付が7日以下の場合
            if($i <= 0){
                $weekly_array[] = date('Y-m-d', mktime(0, 0, 0, $m, $i, $y ));

            //取得した日付が7日を超過するの場合
            }elseif(checkdate( $m, $i, $y ) === FALSE){
                //12月なら一年足して 月を1月追加
                if($m === 12){
                    $y++;
                    $m = 1;
                }
                //日付を頭へ戻す
                $reset_day = $i - $t;

                //月末の日付より$iが大きく、頭に戻す数字が１の場合　月を1ふやす
                if($i > date('t') && $reset_day === 1){
                    $m++;
                }
                // $reset_day
                $weekly_array[] = $y.'-'.$m.'-'. sprintf('%02d', $reset_day);

            }else{
                $weekly_array[] = $y.'-'.$m.'-'.sprintf('%02d', $i);
            }
        } 
        return $weekly_array;
    }

    public static function times($facility_id)
    {   
        $facility_model = new Facility;
        $facility_data = $facility_model->facility_data($facility_id);
        $opening_hours = self::cut_minutes_seconds($facility_data['opening_hours']);
        $closing_hours = self::cut_minutes_seconds($facility_data['closing_hours']);
        for ($i = $opening_hours; $i <= $closing_hours; $i++)
        {
            for ($j = 0; $j <= 30; $j += 30) {
                $times[] = sprintf("%02d:%02d", $i, $j);
            }
        }
        return $times;
    }

    //start_timeの秒数を表示前に削除
    public static function cut_seconds($str)
    {
        $word_count = 3;
        return substr($str,0, strlen($str) - $word_count );
    }

    public static function cut_minutes_seconds($str)
    {
        $word_count = 6;
        return substr($str,0, strlen($str) - $word_count );
    }
}

