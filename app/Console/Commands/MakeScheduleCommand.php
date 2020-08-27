<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\MstSchedule;
use App\Schedule;
use Carbon\Carbon;

class MakeScheduleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        $today = Carbon::today();
        for ($i = 0; $i < 30; $i++) {
            $targetDay=$today->addDay(1);
            $schedules=MstSchedule::all();

            foreach($schedules as $schedule){
                Schedule::FirstOrCreate([
                    'mst_staff_id'=>$schedule->Staff->id,
                    'mst_cource_id'=>$schedule->Cource->id,
                    'schedule_date'=>$targetDay]);
            }
        }


        return 0;
    }
}
