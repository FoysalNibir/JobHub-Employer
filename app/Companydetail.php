<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companydetail extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function industrytypes()
    {
        return $this->belongsToMany('App\Industrytype','companydetail_industrytype', 'companydetail_id', 'industrytype_id');
    }
}
