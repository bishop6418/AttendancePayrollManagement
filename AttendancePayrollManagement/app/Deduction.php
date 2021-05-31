<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deduction extends Model
{
    protected $fillable = [
        'deduction_name',
        'description',
        'value',
        'value_type',
    ];

    public function employees()
    {
        return $this->belongsToMany('App\Employee')->withPivot('amount', 'date_deducted');
    }

}