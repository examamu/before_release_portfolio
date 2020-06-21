<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {   
        $schedule_model = new Schedule();
        $data = $schedule_model->get_schedule_data();
        
        return view('admin',[
            'customers' => $data['customers'],
            'active_customers' => $data['active_customers'],
            'staffs' => $data['staffs'],
            'serviceTypes' => $data['serviceTypes'],
            'week' => config('const.WEEK'),
            'weekly_array' => $data['weekly_array'],
            'times' => $data['times'],
            'facility_data' => $data['facility_data'],
        ]);
    }
}