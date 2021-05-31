<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    protected $fillable = [
        'rate_type',
        'basic_salary',
        'additional'
    ];

    public function employees(){
        return $this->hasMany('App\Employee');
    }
}
