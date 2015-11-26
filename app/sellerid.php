<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sellerid extends Model
{
    protected $table = 'sellers';

    public function sellerreviews()
    {
    	return $this->hasMany('App\sellerreviews', 'id');
    }

    public function sellerratings()
    {
    	return $this->hasMany('App\sellerid', 'id');
    }

}
