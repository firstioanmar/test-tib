<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    
    //set connection
    protected $connection = 'mysql2';

    protected $fillable = [
        'name', 'price', 'description',
    ];
}
