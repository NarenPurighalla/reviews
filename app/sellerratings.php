<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sellerratings extends Model
{
    protected $table = 'seller_ratings';
    protected $hidden = [
    	'id' , 'seller_id' , 'created_at' , 'updated_at'
    ];
}
