<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'position_id',
        'rate_id',
        'schedule_id',

        'first_name', 
        'last_name',
        'email',
        'age',
        'address',
        'contact_number',
        'gender',
        'birthdate',
        'marital_status',
        'date_hired',
        'leave_credits',
        'status'
    ];

    public function position()
    {
        return $this->belongsTo('App\Position');
    }

    public function rate()
    {
        return $this->belongsTo('App\Rate');
    }

    public function schedule()
    {
        return $this->belongsTo('App\Schedule');
    }

    public function attendances()
    {
        return $this->hasMany('App\Attendance');
    }

    public function deductions()
    {
        return $this->belongsToMany('App\Deduction')->withPivot('amount', 'date_deducted');
    }

    public function benefits()
    {
        return $this->belongsToMany('App\Benefit')->withPivot('ref_number');
    }
    
    // public function payrolls()
    // {
    //     return $this->hasMany('App\Payroll');
    // }

    public function payslips()
    {
        return $this->hasMany('App\Payslip');
    }

    public function leaves()
    {
        return $this->hasMany('App\Leave');
    }

}