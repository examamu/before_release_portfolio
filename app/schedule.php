<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class schedule extends Model
{

   public function customer()
   {
       return $this->belongsTo('App\customers');
   }
}
