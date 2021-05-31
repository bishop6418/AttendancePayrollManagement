<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'status_am',
        'status_pm',
        'time_in_am',
        'time_out_am',
        'time_in_pm',
        'time_out_pm',
    ];

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

}
