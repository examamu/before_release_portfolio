<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class customers extends Model
{
    public function schedules()
    {
        return $this->hasMany('App\schedule');
    }
}
