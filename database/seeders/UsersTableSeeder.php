<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();
        $user->firstname = "Max";
        $user->lastname = "Mustermann";
        $user->image = "https://images.pexels.com/photos/10276340/pexels-photo-10276340.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2";
        $user->email = "max@mustermann.at";
        $user->password = bcrypt('maxmustermann');
        $user->save();

        $user = new User();
        $user->firstname = "Jane";
        $user->lastname = "Doe";
        $user->image = "https://images.pexels.com/photos/3268530/pexels-photo-3268530.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=2";
        $user->email = "jane@doe.at";
        $user->password = bcrypt('janedoe');
        $user->save();

        $user = new User();
        $user->firstname = "Lisa";
        $user->lastname = "Mayr";
        $user->image = "https://images.pexels.com/photos/3728598/pexels-photo-3728598.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1";
        $user->email = "lisa@mayr.at";
        $user->password = bcrypt('lisamayr');
        $user->save();
    }
}
