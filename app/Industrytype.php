<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Industrytype extends Model
{
    public function companydetails()
    {
        return $this->belongsToMany('App\Companydetail','companydetail_industrytype', 'industrytype_id', 'companydetail_id');
    }
}
