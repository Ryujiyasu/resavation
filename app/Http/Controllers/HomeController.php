<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $schedules=Schedule::whereNotNull('name')->get();
        return view('calender',['schedules'=>$schedules]);
    }
    public function getDataJson()
    {

        $datas=[];
        $schedules=Schedule::whereNotNull('name')->get();
        foreach ($schedules as $schedule){
            $start_time=$schedule->Cource()->first()->start_time;
            $end_time=$schedule->Cource()->first()->end_time;

            $Start = Carbon::parse(substr($schedule->schedule_date,0,10)." ".$start_time,'Asia/Tokyo');
            $End = Carbon::parse(substr($schedule->schedule_date,0,10)." ".$end_time,'Asia/Tokyo');


            $datas[]=[$schedule,$schedule->Staff()->first(),$schedule->Cource()->first(),$Start,$End];
        }

        return $datas;
    }
    public function editSchedule()
    {

        return view('edit_schedule');
    }
}
