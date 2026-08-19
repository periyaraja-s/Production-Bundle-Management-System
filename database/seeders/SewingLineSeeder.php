<?php

namespace Database\Seeders;

use App\Models\SewingLine;
use Illuminate\Database\Seeder;

class SewingLineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'Line 01 - T-Shirts',
            'Line 02 - Woven Tops',
            'Line 03 - Dresses',
            'Line 04 - Bottoms',
            'Line 05 - Activewear',
        ] as $lineName) {
            SewingLine::query()->updateOrCreate(
                ['line_name' => $lineName],
                ['line_name' => $lineName],
            );
        }
    }
}
