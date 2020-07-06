<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    public static function facility_data($facility_id){
        $facility_data = self::where('id', $facility_id)->first();

        return $facility_data;
    }
}
