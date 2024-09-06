<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $settings = [
            [
                'key' => 'business_min',
                'value' => '34000000',
            ],
            [
                'key' => 'business_max',
                'value' => '35000000',
            ],
            [
                'key' => 'business_note',
                'value' => '',
            ]
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ]);
    }
}
