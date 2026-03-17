<?php

namespace Database\Seeders;

use App\Models\CourierPartner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourierPartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        DB::table('courier_partners')->truncate();

        $couriers = [
            [
                'name' => 'Delhivery',
                'code' => 'DELHIVERY',
                'api_url' => 'https://track.delhivery.com',
                'api_key' => null,
                'api_secret' => null,
                'api_config' => json_encode(['mode' => 'production']),
                'rate_card' => json_encode([
                    '0.5' => 50,
                    '1' => 70,
                    '2' => 90,
                    '5' => 120,
                    '10' => 180,
                    '20' => 250
                ]),
                'cod_charges' => json_encode([
                    'upto_5000' => 30,
                    'upto_10000' => 50,
                    'upto_25000' => 100,
                    'above_25000' => 0.5
                ]),
                'serviceable_pincodes' => json_encode([]),
                'supported_services' => json_encode(['standard', 'express']),
                'is_active' => true,
                'priority' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BlueDart',
                'code' => 'BLUEDART',
                'api_url' => 'https://api.bluedart.com',
                'api_key' => null,
                'api_secret' => null,
                'api_config' => json_encode(['mode' => 'production']),
                'rate_card' => json_encode([
                    '0.5' => 60,
                    '1' => 85,
                    '2' => 110,
                    '5' => 150,
                    '10' => 220,
                    '20' => 300
                ]),
                'cod_charges' => json_encode([
                    'upto_5000' => 35,
                    'upto_10000' => 60,
                    'upto_25000' => 120,
                    'above_25000' => 0.6
                ]),
                'serviceable_pincodes' => json_encode([]),
                'supported_services' => json_encode(['standard', 'express', 'overnight']),
                'is_active' => true,
                'priority' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DTDC',
                'code' => 'DTDC',
                'api_url' => 'https://api.dtdc.com',
                'api_key' => null,
                'api_secret' => null,
                'api_config' => json_encode(['mode' => 'production']),
                'rate_card' => json_encode([
                    '0.5' => 45,
                    '1' => 65,
                    '2' => 85,
                    '5' => 110,
                    '10' => 160,
                    '20' => 220
                ]),
                'cod_charges' => json_encode([
                    'upto_5000' => 25,
                    'upto_10000' => 45,
                    'upto_25000' => 90,
                    'above_25000' => 0.4
                ]),
                'serviceable_pincodes' => json_encode([]),
                'supported_services' => json_encode(['standard', 'express']),
                'is_active' => true,
                'priority' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'FedEx',
                'code' => 'FEDEX',
                'api_url' => 'https://api.fedex.com',
                'api_key' => null,
                'api_secret' => null,
                'api_config' => json_encode(['mode' => 'production']),
                'rate_card' => json_encode([
                    '0.5' => 80,
                    '1' => 120,
                    '2' => 160,
                    '5' => 220,
                    '10' => 300,
                    '20' => 400
                ]),
                'cod_charges' => json_encode([
                    'upto_5000' => 40,
                    'upto_10000' => 70,
                    'upto_25000' => 150,
                    'above_25000' => 0.7
                ]),
                'serviceable_pincodes' => json_encode([]),
                'supported_services' => json_encode(['standard', 'express', 'overnight', 'international']),
                'is_active' => true,
                'priority' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ekart',
                'code' => 'EKART',
                'api_url' => 'https://api.ekartlogistics.com',
                'api_key' => null,
                'api_secret' => null,
                'api_config' => json_encode(['mode' => 'production']),
                'rate_card' => json_encode([
                    '0.5' => 40,
                    '1' => 55,
                    '2' => 75,
                    '5' => 100,
                    '10' => 140,
                    '20' => 200
                ]),
                'cod_charges' => json_encode([
                    'upto_5000' => 20,
                    'upto_10000' => 35,
                    'upto_25000' => 70,
                    'above_25000' => 0.3
                ]),
                'serviceable_pincodes' => json_encode([]),
                'supported_services' => json_encode(['standard']),
                'is_active' => true,
                'priority' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'XpressBees',
                'code' => 'XPRESSBEES',
                'api_url' => 'https://api.xpressbees.com',
                'api_key' => null,
                'api_secret' => null,
                'api_config' => json_encode(['mode' => 'production']),
                'rate_card' => json_encode([
                    '0.5' => 42,
                    '1' => 58,
                    '2' => 80,
                    '5' => 105,
                    '10' => 150,
                    '20' => 210
                ]),
                'cod_charges' => json_encode([
                    'upto_5000' => 22,
                    'upto_10000' => 38,
                    'upto_25000' => 75,
                    'above_25000' => 0.35
                ]),
                'serviceable_pincodes' => json_encode([]),
                'supported_services' => json_encode(['standard', 'express']),
                'is_active' => true,
                'priority' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shadowfax',
                'code' => 'SHADOWFAX',
                'api_url' => 'https://api.shadowfax.in',
                'api_key' => null,
                'api_secret' => null,
                'api_config' => json_encode(['mode' => 'production']),
                'rate_card' => json_encode([
                    '0.5' => 38,
                    '1' => 52,
                    '2' => 72,
                    '5' => 95,
                    '10' => 135,
                    '20' => 190
                ]),
                'cod_charges' => json_encode([
                    'upto_5000' => 18,
                    'upto_10000' => 32,
                    'upto_25000' => 65,
                    'above_25000' => 0.28
                ]),
                'serviceable_pincodes' => json_encode([]),
                'supported_services' => json_encode(['standard', 'express']),
                'is_active' => true,
                'priority' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($couriers as $courier) {
            CourierPartner::create($courier);
        }

        $this->command->info('✅ ' . count($couriers) . ' courier partners seeded successfully!');
    }
}
