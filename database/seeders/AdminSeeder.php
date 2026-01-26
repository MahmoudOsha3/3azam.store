<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{

    public function run()
    {
        Admin::create([
            'name' => 'Mahmoud Abdelrahim' ,
            'email' => 'mahmoud@gmail.com' ,
            'password' => Hash::make('123456789') ,
            'phone' => '01201955377' ,
            'role_id' => 1
        ]) ;
    }
}
