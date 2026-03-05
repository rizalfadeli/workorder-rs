<?php
namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $userRole  = Role::where('name', 'user')->first();

        User::create([
            'name'     => 'Admin Rumah Sakit',
            'email'    => 'admin@rssimrs.id',
            'password' => Hash::make('password'),
            'role_id'  => $adminRole->id,
            'unit'     => 'Manajemen',
        ]);

        User::create([
            'name'     => 'Perawat Budi',
            'email'    => 'budi@rssimrs.id',
            'password' => Hash::make('password'),
            'role_id'  => $userRole->id,
            'unit'     => 'Bangsal Bedah Lt. 2',
        ]);
    }
}