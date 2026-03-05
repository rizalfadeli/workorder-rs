<?php
namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::upsert([
            ['name' => 'admin', 'label' => 'Admin Rumah Sakit'],
            ['name' => 'user',  'label' => 'Pengguna / Pelapor'],
        ], ['name'], ['label']);
    }
}
