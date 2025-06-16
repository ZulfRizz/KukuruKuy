<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Franchise;

class FranchiseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Franchise::create([
            'name' => 'KukuruKuy Bojonegoro',
            'address' => 'Jalan Gajah Mada No. 12, Bojonegoro',
            'phone_number' => '083421456713'
        ]);

        Franchise::create([
            'name' => 'KukuruKuy Surabaya',
            'address' => 'Jalan Pahlawan No. 10, Surabaya',
            'phone_number' => '085411496632'
        ]);
    }
}
