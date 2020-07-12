<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Staff;
use App\User;
use App\Facility;

class UserEditController extends Controller
{

    public static function index(){
        $err_msg = array();
        $user_data = Auth::user();
        $user_id = $user_data->id;
        $staff_data = Staff::where('user_id',$user_id)->first();
        $user_name = Auth::user()->name;
        $user_email = $user_data->email;
        $facility_data = Facility::where('id', $staff_data['facility_id'])->first();

        return view('user_edit',[
            'err_msg' => $err_msg,
            'user_id' => $user_data['id'],
            'user_name' => $user_name,
            'facility_id' => $staff_data['facility_id'],
            'facility_name' => $facility_data['name'],
            'admin' => $staff_data['admin'],
            'email' => $user_email,
        ]);
    }

    public function update(Request $request){
        $post_update_name = $request->input('update_name');
        $post_update_email = $request->input('update_email');
        $post_user_id = $request->input('user_id');
        $staff_data = Staff::where('user_id',$post_user_id)->first();
        $facility_id = $request->input('update_facility');

        $post_data = [
            'staff_id' => $staff_data['id'],
            'user_id' => $post_user_id,
            'name' => $post_update_name,
            'email' => $post_update_email,
            'facility_id' => $facility_id,
        ];
       DB::beginTransaction();
        try{
            if(isset($post_data['facility_id'])){
                Staff::update_staff_data($post_data);
            }
            User::update_user_data($post_data);

            DB::commit();
        }catch(\Exception $e){
           $err_msg[] = 'エラーが発生しています！'.$e->getMessage();
           DB::rollBack();
        }

        return $this->index();
    }
}
