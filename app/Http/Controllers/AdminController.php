<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;
use App\Calendar;
use App\ Facility;
use App\Staff;
use App\Customer;
use App\Schedule_history;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{   

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {   
        $login_user_data = Auth::user();
        $user_facility_id = Staff::staff_data($login_user_data)->facility_id;

        return view('admin',[
            'admin' => Staff::staff_data($login_user_data)['admin'],
            'get_weekly_schedules' => Schedule::search_schedule(),
            'customers' => Customer::customers($user_facility_id),
            'active_customers' => Customer::active_customer($user_facility_id),
            'staffs' => Staff::all_staff_data($user_facility_id),
            'serviceTypes' => \App\ServiceType::All(),
            'week' => config('const.WEEK'),
            'weekly_array' => Calendar::weekly_calendar(),
            'times' => Calendar::times($user_facility_id),
            'facility_data' => Facility::facility_data($user_facility_id),
            'count_date' => count(Calendar::times($user_facility_id)),
            'msg' => 'フォームを入力してください',
        ]);
    }



    public function create(Request $request)
    {   
        $login_user_data = Auth::user();
        $user_facility_id = Staff::staff_data($login_user_data)->facility_id;
        $schedule_model = new Schedule;
        $schedule_history_model = new Schedule_history;
        $times = Calendar::times($user_facility_id);
        $count_date = count($times);
        $err_msg = array();


        for($i = 0; $i < 7; $i++){
            for($j = 0; $j < $count_date; $j++){

                //insertされた利用者名がなければ
                if($request->input('post_schedule_customer_id'.$i.$j) === 'no_customer' ){
                    continue;
                }
                //insertされたサービスタイプがなければ
                if($request->input('post_schedule_service_type'.$i.$j) === 'no_service' ){
                    continue;
                }
                //insertされたスタッフ名がなければ
                if($request->input('post_schedule_staff_id'.$i.$j) === 'no_staff' ){
                    continue;
                }

                $post_schedule_id = $request->input('schedule_id'.$i.$j);

                $weekly_array = Calendar::weekly_calendar();

                //利用時間
                $time = $request->input('time'.$i.$j).':00';

                //利用者名が送られてくるので利用者IDへ置換
                $customer_id = $request->input('post_schedule_customer_id'.$i.$j);

                //利用種別
                $service_type_id = $request->input('post_schedule_service_type'.$i.$j);

                //スタッフ名が送られてくるのでスタッフ情報を取得
                $staff_id = $request->input('post_schedule_staff_id'.$i.$j);
                $staff_data = \App\Staff::where('user_id', $staff_id)->first();

                //施設情報取得
                $facility_id = \App\Facility::find($staff_data['facility_id'])['id'];

                $insert_data_array = [
                    'schedule_id' => $post_schedule_id,
                    'customer_id' => $customer_id,
                    'user_id' => $staff_id,
                    'facility_id'=> $facility_id,
                    'service_type_id' => $service_type_id,
                    'date' => $weekly_array[$i],
                    'start_time' => $time,
                ];
                //スケジュールヒストリーのid取得
                $schedule_history_data = \App\Schedule_history::where('schedule_id',$post_schedule_id)->first();
                $schedule_data = \App\Schedule::find($post_schedule_id);

                //もしスケジュールヒストリーのデータが取得できていれば
                if(!empty($schedule_history_data['schedule_id']) === TRUE)
                {   
                    //ヒストリーの更新メソッドへ流す
                    try{
                        $schedule_history_model->update_schedule_history($insert_data_array);
                    }catch(\Exception $e){
                        $err_msg[] = '過去のスケジュールデータを更新できませんでした';
                    }
                //もしスケジュールヒストリーのデータが入っていなくて
                //スケジュールデータにデータがあったら
                }elseif(!empty($schedule_data['id']) === TRUE){
                    try{
                        $schedule_model->update_schedule($insert_data_array);
                    }catch(\Exception $e){
                        $err_msg[] = 'スケジュールの更新ができませんでした';
                    }
                    
                }else{
                    try{
                        $schedule_model->insert_schedule($insert_data_array);
                    }catch(\Exception $e){
                        $err_msg[] = 'スケジュールの新規登録ができませんでした';
                    }
                }
                
            }
        }
        return $this->index();
    }


}