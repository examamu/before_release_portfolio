<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Staff extends Model
{   
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id')->withDefault();
    }

    public static function all_staff_data($facility_id){
        $all_staff_data = self::with('user')->where('facility_id', $facility_id)->get();

        return $all_staff_data;
    }

    public static function staff_data($login_user_data){
        //認証user_idを利用しログインしているstaff情報取得
        $staff_data = self::with('user')->where('user_id', $login_user_data['id'])->first();

        return $staff_data;
    }

}
