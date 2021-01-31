<?php

namespace App\Http\Controllers;

use App\Facades\Calendar;
use App\MstCource;
use App\MstSchedule;
use App\MstStaff;
use App\MstTime;
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
            $start_time=$schedule->Time()->first()->start_time;
            $end_time=$schedule->Time()->first()->end_time;

            $Start = Carbon::parse(substr($schedule->schedule_date,0,10)." ".$start_time,'Asia/Tokyo');
            $End = Carbon::parse(substr($schedule->schedule_date,0,10)." ".$end_time,'Asia/Tokyo');


            $datas[]=[
                      $schedule,
                      $schedule->Staff()->first(),
                      $schedule->Cource()->first(),
                      $Start,
                      $End
                      ];
        }

        return $datas;
    }
    public function editSchedule($id)
    {
        $staffs = MstStaff::all();

        $schedule=Schedule::find($id);
        $target_date = $schedule->schedule_date;

        $staff=$schedule->Staff()->first();
        $target_schedules=Schedule::where('schedule_date', "=", new Carbon($target_date))
                                    ->get();

        $return=[];
        foreach($target_schedules as $target_schedule){
            if( $target_schedule->Staff()->first() ==$staff){
                array_push($return,[
                    "name"=>$target_schedule->Time()->first()->name,
                    "id"=>$target_schedule->Time()->first()->id,
                ]);
            }
        }

        foreach($target_schedules as $target_schedule){
            if( $target_schedule->Staff ==$staff){
                if($target_schedule->Cource == $schedule->Cource()->first()){
                    $schedule_info=$target_schedule;
                }
            }
        }
        $cources = MstCource::all();
      //dd($schedule_info);
        return view('edit_schedule',[
              "prev_date" => $schedule_info->schedule_date,
              "name" => $schedule_info->name,
              "email" => $schedule_info->email,
              "tel" => $schedule_info->tel,
              "staff" => MstStaff::find($schedule_info->mst_staff_id)->name,
              "staff_id" => $schedule_info->mst_staff_id,
              "cource" => MstCource::find($schedule_info->mst_cource_id)->name,
              "cource_id" => $schedule_info->mst_cource_id,
              "time" => MstTime::find($schedule_info->mst_time_id)->name,
              "time_id" => $schedule_info->mst_time_id,
              "schedule"=>$schedule,
              "staffs"=>$staffs,
              "cources"=>$cources,
              "return"=>$return,
        ]);
    }

    public function editScheduleComplete(Request $request)
    {
        $del_schedule=Schedule::find($request->id);
        $del_schedule->name=null;
        $del_schedule->tel=null;
        $del_schedule->email=null;
        $del_schedule->save();

        $schedule=Schedule::find($request->time_choice);
        $schedule->name=$request->name;
        $schedule->tel=$request->tel;
        $schedule->email=$request->email;
        $schedule->mst_cource_id=$request->cource_choice;
        $schedule->save();


        return redirect('/schedule/listing');
    }
    public function cancelSchedule(Request $request)
    {
        $del_schedule=Schedule::find($request->id);
        $del_schedule->name=null;
        $del_schedule->tel=null;
        $del_schedule->email=null;
        $del_schedule->mst_cource_id=null;
        $del_schedule->save();

        //return redirect('/schedule/listing');
    }

    public function listing(){

      $schedules=Schedule::whereNotNull('name')->get();
      return view('listing',['schedules'=>$schedules]);

    }

    public function reception(){

          //$schedules=Schedule::whereNotNull('name')->get();
          return view('reception');

        }
}
