<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // for ($i = 1; $i <= 10; $i++) {
        //   DB::table('members')->insert([
        //             'member_id' => $i + 1000,
        //             'name' => 'メンバー'.$i,
        //             'tel' => '09012345678',
        //             'email' => 'example@gmail.com',
        //
        //   ]);
        //   DB::table('mst_cources')->insert([
        //             'name' => 'コース'.$i,
        //             'color' => 'red',
        //   ]);
        //   DB::table('mst_staff')->insert([
        //             'name' => 'スタッフ'.$i,
        //   ]);
        //
        // }
        // for ($i = 9; $i <= 21; $i++) {
        //   DB::table('mst_times')->insert([
        //             'name' => $i.':00',
        //             'start_time' => $i.':00',
        //             'end_time' => $i.':30',
        //   ]);
        //   DB::table('mst_times')->insert([
        //             'name' => $i.':30',
        //             'start_time' => $i.':30',
        //             'end_time' => $i+1 .':00',
        //   ]);
        // }
        for ($i = 1; $i <= 10; $i++) {
          for ($j = 1; $j <= 26; $j++) {
            DB::table('mst_schedules')->insert([
                      'mst_staff_id' => $i,
                      'mst_time_id' => $j,
            ]);
          }
        }
        // DB::table('mst_statuses')->insert([
        //     'name' => '予約可能',
        //     'busy_flg' => 0,
        // ]);
    }
}
