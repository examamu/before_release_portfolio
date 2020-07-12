<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use App\Staff;
use App\Facility;

class UserEditTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetUserId()
    {   
        $user_data = Auth::user();
        $user_id = $user_data->id;

        $this->assertEquals(1,$user_id);

        $this->assertTrue(true);
    }

    public function testGetStaffData()
    {
        $staff_data = Staff::where('user_id',$this->user_id)->first();
        $staff_id = $staff_data['id'];

        $this->assertEquals(1,$staff_id);
    }

    // public function test()
    // {
    //     $facility_data = Facility::where('id', $this->staff_data['facility_id'])->first();
    // }
}
