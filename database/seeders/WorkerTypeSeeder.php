<?php

namespace Database\Seeders;

use App\Models\WorkerType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            'Muratore',
            'Elettricista',
            'Idraulico',
            'Carpentiere',
            'Pittore',
        ];

        foreach ($types as $type) {
            WorkerType::firstOrCreate(['name' => $type]);
        }
    }
}
