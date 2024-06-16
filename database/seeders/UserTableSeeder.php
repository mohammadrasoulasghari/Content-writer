<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                "name" => "محمدرسول",
                "email" => "admin@gmail.com",
                "password" => bcrypt("admin@1234")
            ],
            [
                "name" => "محسن موحد",
                "email" => "movahed@gmail.com",
                "password" => bcrypt("admin@1234")
            ],
            [
                "name" => "سحر پاشایی",
                "email" => "pashaei@gmail.com",
                "password" => bcrypt("admin@1234")
            ],
            [
                "name" => "لقمان آوند",
                "email" => "avand@gmail.com",
                "password" => bcrypt("admin@1234")
            ],
            [
                "name" => "مجید مظاهری",
                "email" => "mazaheri@gmail.com",
                "password" => bcrypt("admin@1234")
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
