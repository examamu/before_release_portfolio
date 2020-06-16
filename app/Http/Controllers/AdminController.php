<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Schedule;

class AdminController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {   
        $opening_hours = '8';
        $closing_hours = '17';

        for ($i = $opening_hours; $i <= $closing_hours-1; $i++) {
            for ($j = 0; $j <= 30; $j += 30) {
                $times[] = sprintf("%02d:%02d\n", $i, $j);
            }
        }

        $schedule_model = new Schedule();
        $weekly_array = $schedule_model->calendar();
        $week = ['日','月','火','水','木','金','土'];
        $facility_id = 112;
        $customers = \App\Customers::where('status',1)->get();
        $staffs = \App\User::All();
        $serviceTypes = \App\ServiceTypes::All();
        
        return view('admin',[
            'customers' => $customers,
            'staffs' => $staffs,
            'serviceTypes' => $serviceTypes,
            'week' => $week,
            'weekly_array' => $weekly_array,
            'times' => $times,
        ]);
    }
}