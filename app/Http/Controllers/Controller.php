<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// Base Controller: Kelas induk yang mewarisi (extends) Controller utama framework
class Controller extends BaseController
{
    // Menggunakan Traits standar Laravel untuk otentikasi dan validasi request
    use AuthorizesRequests, ValidatesRequests;
}
