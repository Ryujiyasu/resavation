<?php

namespace App\Http\Controllers;

use App\Facades\Calendar;
use App\MstStaff;
use App\Services\CalendarService;
use Illuminate\Http\Request;
use App\MstSchedule;
use App\Member;
use App\MstCource;
use App\Schedule;
use Carbon\Carbon;
use App\Mail\reserveMail;
use Mail;

class CalendarController extends Controller
{
    private $service;

    public function __construct(CalendarService $service)
    {
        $this->service = $service;
    }


    public function index(Request $request)
    {
        $staffs = MstStaff::all();
        $target_staff = $request->input('staff',0);
        $month_check =(integer)Calendar::getMonthCheck();

        if ($month_check ==0){
            $month_check=11;
        }else{
            $month_check-=1;
        }
        $cources = MstCource::all();
        return view('booking',[
            "cources"=>$cources,
            "month_check"=>$month_check,
            "target_staff" =>$target_staff,
            "staffs"=>$staffs,
            'weeks'         => Calendar::getWeeks(),
            'month'         => Calendar::getMonth(),
            'prev'          => Calendar::getPrev(),
            'next'          => Calendar::getNext(),
        ]);
    }
    public function choice($year,$month,$day)
    {
        $target_date= new Carbon($year."-".$month."-".$day);

        $schedules=MstSchedule::all();

        foreach($schedules as $schedule){
            Schedule::FirstOrCreate([
                'mst_staff_id'=>$schedule->Staff->id,
                'mst_cource_id'=>$schedule->Cource->id,
                'schedule_date'=>$target_date]);
        }

        $target_schedules=Schedule::where('schedule_date', "=", $target_date)->get();

        return view('choice',
            ['target_schedules'=>$target_schedules,
                'year'=>$year,
                'month'=>$month,
                'day'=>$day,
            ]);
    }
    public function booking($id)
    {

        $schedule=Schedule::find($id);
        return view('booking',['schedule'=>$schedule]);
    }

    public function scheduleGet(Request $request){
        $date = $request->get("date");
        $staff=$request->get("staff");

        $target_date= new Carbon($date);

        $return=[];
        $target_schedules=Schedule::where('schedule_date', "=", $target_date)
                                    ->whereNull('name')->get();
        foreach($target_schedules as $target_schedule){
            if( $target_schedule->Staff->id ==$staff){
                array_push($return,[
                    "name"=>$target_schedule->Time()->first()->name,
                    "id"=>$target_schedule->id,
                ]);
            }
        }
        return ['schedules'=>$return];
    }

    public function memberGet(Request $request)
    {
        $member_id=$request->get('member_id');
        $member = Member::where("member_id",$member_id)->first();
        return ['data'=>$member];
    }

    public function book(Request $request)
    {
        $schedule=Schedule::find($request->get("schedule_choice"));

        $schedule->name=$request->get("name");
        $schedule->email=$request->get("email");
        $schedule->tel=$request->get("tel");
        $schedule->mst_cource_id=$request->cource_choice;

      //  dd($schedule->Time()->first()->start_time);
        $schedule->save();

        $data = $schedule;
        Mail::to($schedule->email)->send(new reserveMail($data));

        return $data; //予約完了

    }
    public function mySchedule(Request $request,$id)
    {


        $member = Member::where("member_id",$id)->first();

        $schedules=Schedule::where('tel', "=", $member->tel)->get();

        return view('mySchedule',['schedules'=>$schedules]);
    }
    public function staffGet(){
      $staff = Mststaff::get();
      return ['staffSelect'=>$staff];
    }
    public function courceGet(){
      $cource = MstCource::get();
      return ['courceSelect'=>$cource];
    }
}
