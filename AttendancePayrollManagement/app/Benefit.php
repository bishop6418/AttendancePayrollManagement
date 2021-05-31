<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function employees()
    {
        return $this->belongsToMany('App\Employee')->withPivot('ref_number');
    }

    public function compensation()
    {
        return $this->belongsTo('App\Compensation');
    }

}
