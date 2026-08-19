<?php

namespace Database\Seeders;

use App\Models\Buyer;
use App\Models\Style;
use Illuminate\Database\Seeder;

class StyleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $stylesByBuyer = [
            'Apex Apparel Co.' => ['APX-TSH-1001', 'APX-HOD-1002', 'APX-JOG-1003'],
            'Northstar Fashion Group' => ['NSF-DRS-2001', 'NSF-BLS-2002', 'NSF-SKT-2003'],
            'Evergreen Outfitters' => ['EVO-CHN-3001', 'EVO-FLN-3002', 'EVO-PTN-3003'],
            'Harbor & Thread' => ['HBT-POLO-4001', 'HBT-OCS-4002'],
            'Summit Activewear' => ['SMA-TEE-5001', 'SMA-LEGG-5002', 'SMA-TRK-5003'],
        ];

        foreach ($stylesByBuyer as $buyerName => $styleNumbers) {
            $buyer = Buyer::query()->where('buyer_name', $buyerName)->firstOrFail();

            foreach ($styleNumbers as $styleNumber) {
                Style::query()->updateOrCreate(
                    ['buyer_id' => $buyer->id, 'style_no' => $styleNumber],
                    ['buyer_id' => $buyer->id],
                );
            }
        }
    }
}
