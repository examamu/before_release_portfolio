<?php

namespace App\Http\Middleware;

use Closure;

class MakeIndicateData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {




        
    //     $week = ['日','月','火','水','木','金','土'];
    //     foreach($customers as $customer){
    //         //取得時になくなった0を取得し直し
    //         $int = sprintf('%07d',$customer->date_of_use);
            
    //         //一つずつ配列に格納
    //         $int_array = str_split($int);

    //         $i = 0;
    //         foreach ( $int_array as $value ){
    //         //$customer->data_of_useの曜日の項目の桁が0なら
    //             if($value === '1'){
    //                 $int_array[$i] = $week[$i];
    //             }else{
    //                 //削除
    //                 unset($int_array[$i]);
    //             }
    //             $i++;
    //         }
    //         $customer->date_of_use = implode(',', $int_array);
    //         //利用者の介護度を表示
    //         if($customer->customer->care_type === 1){
    //             $customer->customer->care_type = '要介護';
    //         }else{
    //             $customer->customer->care_type = '要支援';
    //         }
    //     }

        // return $next($request);
}
