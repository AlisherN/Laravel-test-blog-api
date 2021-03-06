<?php

namespace Database\Seeders;

use App\Models\User;
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
        $users = [
            [
                'email' => env('TEST_USER_EMAIL', 'alishern@mail.uz'),
                'name' => env('TEST_USER_NAME', 'alishern'),
            ],
        ];

        foreach ($users as $record) {
            $user = new User();
            $user->fill($record);
            $user->setAttribute('password', bcrypt('admin123'));
            $user->save();
        }

        User::factory(rand(5, 10))->create();
    }
}
