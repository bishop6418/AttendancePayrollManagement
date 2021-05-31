<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'shift',
        'time_in',
        'time_out'
    ];

    public function employees()
    {
        return $this->hasMany('App\Employee');
    }
}