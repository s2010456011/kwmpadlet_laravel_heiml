<?php

namespace Database\Seeders;

use App\Models\Padlet;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Psy\Readline\Hoa\Console;

class PadletsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {

        $padlet = new Padlet();
        $padlet->title = "Neues Padlet";
        $padlet->description = "Beschreibungstext des Padlets";
        $padlet->is_public = true;
        //$padlet->user_id = 1;
        $user = User::first();
        $padlet->user()->associate($user);
        $padlet->save();

        //padlet_user --> connect user, role and padlet
        //get all IDs from Users and Roles
        //sync them all in one table
        $users = User::all()->pluck("id");
        $roles = Role::all()->pluck("id");
        //$padlet->users()->sync($users);
        //$padlet->roles()->sync($roles);
        $padlet->users()->syncWithPivotValues($users, ['role_id' => $roles[1]]);
        $padlet->save();

        $padlet2 = new Padlet();
        $padlet2->title = "Zweites Padlet";
        $padlet2->description = "Beschreibungstext des zweiten Padlets";
        $padlet2->is_public = false;
        $user = User::first();
        $padlet2->user()->associate($user);
        $padlet2->save();
    }
}
