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
     */
    public function create()
    {       
        for($i = 0; $i < 7; $i++){
            for($j = 0; $j < 7; $j++){
                $schedule_model = new Schedule();
                $weekly_array = $schedule_model->calendar();
                $time = trim($_POST['time'.$i.$j]).':00';
                $customer_name = $_POST['post_schedule_customer_name'.$i.$j];
                $customer_id = \App\Customers::where('name', $customer_name)->first();
                $service_type = $_POST['post_schedule_service_type'.$i.$j];
                $staff_name = $_POST['post_schedule_staff_name'.$i.$j];
                $staff_id = \App\User::where('name', $staff_name)->first();
            
                $schedule_model->customer_id = $customer_id['id'];
                $schedule_model->user_id = $staff_id['id'];
                $schedule_model->facility_id = 112;
                $schedule_model->date = $weekly_array[$i];
                $schedule_model->start_time = $time;

                $schedule_model->save();
            }
        }
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
