<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Handphone extends Model
{
    use HasFactory;



    /*
    relation tables users
    */
    public function user()
    {
        return $this->belongsTo("App\Models\User");
    }
}
