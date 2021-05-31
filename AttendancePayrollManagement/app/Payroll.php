<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    protected $fillable = [
        'start_date',
        'end_date',
    ];

    public function payslips()
    {
        return $this->hasMany('App\Payslip');
    }
}
