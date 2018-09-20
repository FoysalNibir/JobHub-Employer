<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Benefit extends Model
{
    public function addjobinfos()
    {
        return $this->belongsToMany('App\Addjobinfo');
    }
}
