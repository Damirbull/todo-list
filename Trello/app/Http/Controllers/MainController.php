<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
class MainController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }


}
