<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
class FakerController extends Controller
{

    public function generate()
    {
        $faker = Faker::create();

        for ($i = 0; $i < 5; $i++) { 
            User::create([
                'name' => $faker->name(),
                'email' => $faker->unique()->safeEmail(),
                'email_verified_at' => now(),
                'password' => bcrypt('123123'), // Пароль по умолчанию '123123'
                'remember_token' => Str::random(10),
            ]);
        }
        return 'Создалось 5 рандомных аккаунтов, пароль от аккаунтов 123123';
    }

}