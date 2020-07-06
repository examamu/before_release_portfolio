<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Schedule;
use App\Schedule_history;

class ScheduleTest extends TestCase
{
    /**
     * A basic test example.
     * @test
     * @return void
     */
    public function test_get_exist_data()
    {   
        $schedule_model = new Schedule;
        $weekly_calendar = $schedule_model->calendar();
        $closing_hours = '17:00:00';
        $facility_business_hour = $schedule_model->times('08:00:00','17:00:00');

        $i = 0;
        foreach($weekly_calendar as $date)
        {
            foreach($facility_business_hour as $hour)
            {
                //日付が今日より先かつ時間が今より後の場合
                if($date < date('Y-m-d') && $hour < date('H:i:s')){
                    continue;
                }
                $get_schedule_array[] = $i;
                $i++;
            }
        }

        $this->assertNotNull($get_schedule_array);
    }
}
