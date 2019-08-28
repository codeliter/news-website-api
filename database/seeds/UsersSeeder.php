<?php

use App\Models\Users;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = [
            [
                'name' => "Admin User",
                'username' => 'admin',
                'password' => 'admin'
            ],
            [
                'name' => "Basic User",
                'username' => 'user',
                'password' => 'user'
            ],
        ];

        foreach ($users as $user) {
            $user['password'] = password_hash($user['password'], PASSWORD_BCRYPT);
            Users::firstOrCreate(['username'=>$user['username']], $user);
        }
    }
}
