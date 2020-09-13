<?php

namespace App\Services;

use Carbon\Carbon;
use App\ScheduleFlg;

class CalendarService
{
    /**
     * カレンダーデータを返却する
     *
     * @return array
     */
    public function getWeeks()
    {
        $weeks = [];
        $week = '';

        $dt = new Carbon(self::getYm_firstday());
        $Schedule_flgs=ScheduleFlg::whereMonth('date','=',$dt->month)->get();


        $day_of_week = $dt->dayOfWeek;     // 曜日
        $days_in_month = $dt->daysInMonth; // その月の日数

        // 第 1 週目に空のセルを追加
        $week .= str_repeat('<td></td>', $day_of_week);

        for ($day = 1; $day <= $days_in_month; $day++, $day_of_week++) {
            $date = self::getYm() . '-' . $day;
            $schedule_flg=False;
            foreach ($Schedule_flgs as $value){
                if($value->date==$date){
                    $schedule_flg =True;
                }
            }
            if($schedule_flg){
                if (Carbon::now()->format('Y-m-j') === $date) {
                    $week .= '<td class="day today">' . $day;
                } else {
                    $week .= '<td class="day">' . $day;
                }
                $week .= '×</td>';

            }else{
                if (Carbon::now()->format('Y-m-j') === $date) {
                    $week .= '<td class="day today">' . $day;
                } else {
                    $week .= '<td class="day">' . $day;
                }
                $week .= '</td>';

            }

            // 週の終わり、または月末
            if (($day_of_week % 7 === 6) || ($day === $days_in_month)) {
                if ($day === $days_in_month) {
                    $week .= str_repeat('<td></td>', 6 - ($day_of_week % 7));
                }
                $weeks[] = '<tr>' . $week . '</tr>';
                $week = '';
            }
        }
        return $weeks;
    }


    public function getMonth()
    {
        return Carbon::parse(self::getYm_firstday())->format('Y年m月');
    }

    public function getMonthCheck()
    {
        return Carbon::parse(self::getYm_firstday())->format('m');
    }


    /**
     * prev 文字列を返却する
     *
     * @return string
     */
    public function getPrev()
    {
        return Carbon::parse(self::getYm_firstday())->subMonthsNoOverflow()->format('Y-m');
    }

    /**
     * next 文字列を返却する
     *
     * @return string
     */
    public function getNext()
    {
        return Carbon::parse(self::getYm_firstday())->addMonthNoOverflow()->format('Y-m');
    }

    /**
     * GET から Y-m フォーマットを返却する
     *
     * @return string
     */
    private static function getYm()
    {
        if (isset($_GET['ym'])) {
            return $_GET['ym'];
        }
        return Carbon::now()->format('Y-m');
    }

    /**
     * 2019-09-01 のような月初めの文字列を返却する
     *
     * @return string
     */
    private static function getYm_firstday()
    {
        return self::getYm() . '-01';
    }
}
