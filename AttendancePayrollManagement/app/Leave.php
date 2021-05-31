<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{

    protected $fillable = [
        'employee_id',
        'from',
        'to',
        'status',
        'no_of_days',
        'description',
    ];

    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }
}