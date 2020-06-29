<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule_history extends Model
{   

    public function customer()
    {
        return $this->belongsTo('App\customer');
    }

    public function create()
    {
        //
    }
}