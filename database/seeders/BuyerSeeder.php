<?php

namespace Database\Seeders;

use App\Models\Buyer;
use Illuminate\Database\Seeder;

class BuyerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach ([
            'Apex Apparel Co.',
            'Northstar Fashion Group',
            'Evergreen Outfitters',
            'Harbor & Thread',
            'Summit Activewear',
        ] as $buyerName) {
            Buyer::query()->updateOrCreate(
                ['buyer_name' => $buyerName],
                ['buyer_name' => $buyerName],
            );
        }
    }
}
