<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usage_situation extends Model
{
    public function customer(){
        return  $this->belongsTo('App\Customer');
    }

    public function customer_status_1()
    {
            return $this->customer()->where('status', 1);
    }
}
