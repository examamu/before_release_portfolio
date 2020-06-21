<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //scheduleモデルを使用してスケジュール一覧を取得
        $today = date('Y-m-d');
        $current_time = date('H:i:s');
        $today_schedules = \App\Schedule::where('date',$today)->limit(10)->offset(1)->orderBy('start_time','asc')->get();
        $next_schedule = \App\Schedule::where('start_time', '>=', $current_time)->orderBy('start_time','asc')->first();
        return view('home',[
            'schedules' => $today_schedules,
            'next_schedule' => $next_schedule,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * 
     *
     */
    public function create(Request $request)
    {       
        for($i = 0; $i < 7; $i++){
            for($j = 0; $j < 7; $j++){

                if($request->input('post_schedule_customer_name'.$i.$j) === 'no_customer' ){
                    continue;
                }

                if($request->input('post_schedule_service_type'.$i.$j) === 'no_service' ){
                    continue;
                }

                if($request->input('post_schedule_staff_name'.$i.$j) === 'no_staff' ){
                    continue;
                }
                
                $schedule_model = new Schedule();
                $weekly_array = $schedule_model->calendar();

                //利用時間
                $time = trim($request->input('time'.$i.$j)).':00';

                //利用者名が送られてくるので利用者IDへ置換
                $customer_name = $request->input('post_schedule_customer_name'.$i.$j);
                $customer_data = \App\Customer::where('name', $customer_name)->first();

                //利用種別
                $service_type_id = $request->input('post_schedule_service_type'.$i.$j);

                //スタッフ名が送られてくるのでスタッフ情報を取得
                $staff_name = $request->input('post_schedule_staff_name'.$i.$j);
                $staff_data = \App\User::with('staff')->where('name', $staff_name)->first();

                //施設情報取得
                $facility_data = \App\Facility::find($staff_data->staff->facility_id)->first();


                $schedule_model->customer_id = $customer_data['id'];
                $schedule_model->user_id = $staff_data['id'];
                $schedule_model->facility_id = $facility_data['id'];
                $schedule_model->service_type_id = $service_type_id;
                $schedule_model->date = $weekly_array[$i];
                $schedule_model->start_time = $time;

                $schedule_model->save();
            }
        }

        return view('admin');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
