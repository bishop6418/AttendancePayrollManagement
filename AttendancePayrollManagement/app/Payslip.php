<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payslip extends Model
{
    protected $fillable = [
        'payroll_id',
        'employee_id',

        'total_weekdays',
        'counts_late',
        'days_absent',
        'days_leave',
        'days_worked',
        'days_saturday',

        'basic_salary',
        'additional',
        'leave_pay',
        'saturday_pay',
        // 'deduc_late',
        // 'deduc_absent',

        'gross_salary',
        'total_deduction',
        'net_salary',
    ];
    
    public function employee()
    {
        return $this->belongsTo('App\Employee');
    }

    public function payroll()
    {
        return $this->belongsTo('App\Payroll');
    }
}