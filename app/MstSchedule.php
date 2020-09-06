<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MstSchedule extends Model
{
    public function Staff()
    {

        return $this->belongsTo('App\MstStaff','mst_staff_id');
    }

    public function Cource()
    {
        return $this->belongsTo('App\MstCource','mst_cource_id');
    }
    public function Time()
    {
        return $this->belongsTo('App\MstTime','mst_time_id');
    }
}
