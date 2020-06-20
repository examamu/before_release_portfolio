<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customer extends Model
{
    public function schedules()
    {
        return $this->hasMany('App\schedule');
    }

    public function usage_situations()
    {
        return  $this->hasMany('App\Usage_situation');
    }
}
