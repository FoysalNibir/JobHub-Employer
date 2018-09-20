<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Browsecategory extends Model
{
	public function users()
	{
		return $this->belongsToMany('App\User');
	}

	public function jobinfo()
	{
		return $this->belongsToMany('App\Jobinfo');
	}
}
