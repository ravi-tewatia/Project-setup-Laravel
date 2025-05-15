<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
                ['full_name' => 'E2logy', 'email' => 'admin@e2logy.com', 'password' => bcrypt('123456'), 'created_at' => NOW()],
                ['full_name' => 'Elan', 'email' => 'admin@elan.com', 'password' => bcrypt('123456'), 'created_at' => NOW()],
            ];
        User::insert($users);
    }
}
