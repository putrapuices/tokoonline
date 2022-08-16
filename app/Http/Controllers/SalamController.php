<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SalamController extends Controller
{
    public function beriSalam(){
      return view("salam.index", ["kalimat" => "Halo Selamat Datang"]);
   }
}
