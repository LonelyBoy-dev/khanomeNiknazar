<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Admin;
use App\Models\Timing;
use Nette\Utils\DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (!Auth::guard('admin')->user()){
            return redirect('admin/login');
        }

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = "داشبورد";
        $Active = "dashboard";
        return view('admin.dashboard.index',compact(['title','Active']));
    }

    public function make_Timing()
    {
        Timing::query()->truncate();
        $start_date = new DateTime(verta()->format('Y-n-j') . ' ' . '08:00');

        $since_start = $start_date->diff(new DateTime(verta()->format('Y-n-j') . ' ' . '23:00'));
        $start_H = $since_start->h;
        $diffs = $start_H * 60;
        $timeMinutes = 10;
        $timeHour = $timeMinutes / 60;

        $startTimeMorning = explode(':', '08:00');
        $startTimeMorning_Minutes = $startTimeMorning[0] * 60;
        $startTimeMorning_second = $startTimeMorning[1];
        $startTimeMorning = $startTimeMorning_Minutes + $startTimeMorning_second;

        $endTimeMorning = explode(':', '24:00');
        $endTimeMorning_Minutes = $endTimeMorning[0] * 60;
        $endTimeMorning_second = $endTimeMorning[1];
        $endTimeMorning = $endTimeMorning_Minutes + $endTimeMorning_second;
        for ($i = 1, $TSM = $startTimeMorning; $i <= $start_H, $TSM < $endTimeMorning; $i++, $TSM += 10) {

            $time_stepss = $TSM / 60;
            $time_steps = explode('.', $time_stepss);
            if (@$time_steps[1]) {
                $time_step = $time_steps[0] . ':' . round($time_steps[1] * 6.1);

            } else {
                $time_step = $time_stepss . ':00';
            }

            $time_stepss_2 = ($TSM + 10) / 60;

            $time_steps_2 = explode('.', $time_stepss_2);
            if (@$time_steps_2[1]) {
                $time_step_2 = $time_steps_2[0] . ':' . round($time_steps_2[1] * 6.1);

            } else {
                $time_step_2 = $time_stepss_2 . ':00';
            }
            $time_1 = explode(':', $time_step);
            $time_2 = explode(':', $time_step_2);


            $hour_min = $time_1[0];
            $hour_max = $time_2[0];
            $minute_min = $time_1[1][0] . $time_1[1][1];
            $minute_max = $time_2[1][0] . $time_2[1][1];

            if ($minute_min[1]!=0){
                $minute_min[1]=0;
            }
            $minute_min=$minute_min[0].$minute_min[1];

            if ($minute_max[1]!=0){
                $minute_max[1]=0;
            }
            $minute_max=$minute_max[0].$minute_max[1];


            $hour_1 = $hour_min . ':' . $minute_min;
            $hour_2 = $hour_max . ':' . $minute_max;


            $new_Timing=new Timing();
            $new_Timing->startTime=$hour_1;
            $new_Timing->endTime=$hour_2;
            $new_Timing->save();

        }
        session()->put('store-success', 'زمان بندی ها ایجاد شدند');
        return redirect('/admin/dashboard');
    }
}
