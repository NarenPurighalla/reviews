<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class sellerreviews extends Model
{
    protected $table = 'seller_reviews';
    protected $hidden = [

    'id' , 'seller_id' , 'hash_id' , 'created_at' , 'updated_at'

    ];
}
