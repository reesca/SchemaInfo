<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	public function users()
	{
	    return $this->hasMany('App\User')->get();
	}

	public function permissions()
	{
	    return $this->hasMany('App\Permission');
	}
}

