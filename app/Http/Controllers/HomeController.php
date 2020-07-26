<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;

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


        $schedules=Schedule::whereNotNull('name')->get();

        return $schedules;
    }
}
