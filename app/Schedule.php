<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = ['mst_cource_id', 'mst_staff_id','mst_time_id','schedule_date'];

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
