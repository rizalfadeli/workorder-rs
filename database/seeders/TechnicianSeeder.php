<?php
namespace Database\Seeders;

use App\Models\Technician;
use Illuminate\Database\Seeder;

class TechnicianSeeder extends Seeder
{
    public function run(): void
    {
        $technicians = [
            ['name' => 'Agus Santoso',  'phone' => '081234567890', 'specialty' => 'Listrik & Panel'],
            ['name' => 'Dedi Kurniawan','phone' => '082345678901', 'specialty' => 'Mekanik & HVAC'],
            ['name' => 'Eko Prasetyo',  'phone' => '083456789012', 'specialty' => 'IT & Jaringan'],
            ['name' => 'Fajar Nugroho', 'phone' => '084567890123', 'specialty' => 'Alat Medis'],
        ];

        foreach ($technicians as $tech) {
            Technician::create(array_merge($tech, ['is_active' => true]));
        }
    }
}