<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Staff;

class User extends Authenticatable
{
    use Notifiable;

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function update_user_data($staff_data){
        $update = self::find($staff_data['user_id']);
        $update->name = $staff_data['name'];
        $update->email = $staff_data['email'];
        $update->save();
    }
}