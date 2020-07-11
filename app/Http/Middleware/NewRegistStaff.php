<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Staff;

class NewRegistStaff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {   
        $user_id = Auth::id();
        $check_staff_data = \App\Staff::where('user_id',$user_id)->first();
        if (empty($check_staff_data)) {
            DB::table('staff')->insert([
                'user_id' => $user_id,
                'facility_id' => 1,
                'admin' => 0,
            ]);
        }
        return $next($request);
    }
}
