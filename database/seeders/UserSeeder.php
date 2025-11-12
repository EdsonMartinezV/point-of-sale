<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{
        $users = [
            [
                'nickname' => 'superadmin',
                'email' => 'superadmin@mail.com',
                'password' => Hash::make('SUperadmin14!'),
                'role' => 'admin'
            ],
            [
                'nickname' => 'admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('ADmin1!'),
                'role' => 'owner'
            ],
            [
                'nickname' => 'caja2',
                'email' => 'caja2@mail.com',
                'password' => Hash::make('CAja2!'),
                'role' => 'cashier'
            ],
        ];

        foreach($users as $user){
            $newUser = User::create(Arr::except($user, ['role']));
            $role = Role::where('name', $user['role'])->first();
            $newUser->roles()->attach($role->id);
        }
    }
}
