<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compensation extends Model
{
    protected $fillable = [

        'from_amount',
        'to_amount',
        'employee_share',
        'employer_share',
    ];

    public function benefits()
    {
        return $this->hasMany('App\Benefit');
    }

}
