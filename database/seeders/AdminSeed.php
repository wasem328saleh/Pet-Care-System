<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $full_name="Pet Care System Admin";
        $email="pet.care.system.admin@gmail.com";
        $password="12345678";
        $phone_number="0945541233";
        $is_admin=true;

        DB::table('users')->insert([
            'full_name'=>$full_name,
            'email'=>$email,
            'password'=>Hash::make($password),
            'phone_number'=>$phone_number,
            'is_admin'=>$is_admin
        ]);
    }
}
