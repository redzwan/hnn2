<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Vanilo\Shipment\Models\Carrier;
use Vanilo\Shipment\Models\ShippingMethod;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure Malaysia exists in countries table (required for addresses)
        \DB::table('countries')->updateOrInsert(
            ['id' => 'MY'],
            ['name' => 'Malaysia', 'phonecode' => 60, 'is_eu_member' => false]
        );

        // NinjaVan
        $ninjavan = Carrier::firstOrCreate(
            ['name' => 'NinjaVan'],
            ['is_active' => true]
        );

        ShippingMethod::firstOrCreate(
            ['name' => 'NinjaVan Standard Delivery', 'carrier_id' => $ninjavan->id],
            [
                'is_active' => true,
                'calculator' => 'flat_fee',
                'configuration' => [
                    'title' => 'NinjaVan Standard Delivery',
                    'cost' => 10.00,
                    'free_threshold' => 200.00,
                ],
                'eta_min' => 3,
                'eta_max' => 5,
                'eta_units' => 'days',
            ]
        );

        ShippingMethod::firstOrCreate(
            ['name' => 'NinjaVan Express Delivery', 'carrier_id' => $ninjavan->id],
            [
                'is_active' => true,
                'calculator' => 'flat_fee',
                'configuration' => [
                    'title' => 'NinjaVan Express Delivery',
                    'cost' => 18.00,
                    'free_threshold' => null,
                ],
                'eta_min' => 1,
                'eta_max' => 2,
                'eta_units' => 'days',
            ]
        );

        // J&T Express
        $jnt = Carrier::firstOrCreate(
            ['name' => 'J&T Express'],
            ['is_active' => true]
        );

        ShippingMethod::firstOrCreate(
            ['name' => 'J&T Express Standard', 'carrier_id' => $jnt->id],
            [
                'is_active' => true,
                'calculator' => 'flat_fee',
                'configuration' => [
                    'title' => 'J&T Express Standard',
                    'cost' => 8.00,
                    'free_threshold' => 200.00,
                ],
                'eta_min' => 3,
                'eta_max' => 7,
                'eta_units' => 'days',
            ]
        );

        ShippingMethod::firstOrCreate(
            ['name' => 'J&T Express Economy', 'carrier_id' => $jnt->id],
            [
                'is_active' => true,
                'calculator' => 'flat_fee',
                'configuration' => [
                    'title' => 'J&T Express Economy',
                    'cost' => 6.00,
                    'free_threshold' => 200.00,
                ],
                'eta_min' => 5,
                'eta_max' => 10,
                'eta_units' => 'days',
            ]
        );

        $this->command->info('✅ Shipping carriers and methods seeded: NinjaVan & J&T Express');
    }
}
