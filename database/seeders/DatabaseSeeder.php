<?php

namespace Database\Seeders;

use App\Models\ScheduleScheme;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate(['email' => env('APP_ADMIN_EMAIL')], [
            'name' => 'Староста',
            'email' => env('APP_ADMIN_EMAIL'),
            'password' => Hash::make(env('APP_ADMIN_PASS'))
        ]);

        $scheme = [
            ['9:30', '11:05'],
            ['11:20', '12:55'],
            ['13:10', '14:45'],
            ['15:25', '17:00'],
            ['17:15', '18:50'],
        ];

        foreach ($scheme as $unit) {
            ScheduleScheme::firstOrCreate([
                'start_time' => $unit[0],
                'end_time' => $unit[1],
            ], [
                'start_time' => $unit[0],
                'end_time' => $unit[1],
            ]);
        }

        $settings = [
            SystemSetting::START_YEAR_SETTING => '01.09.2021',
            SystemSetting::COMMUNITY_CHAT_ID_SETTING => '',
            SystemSetting::VK_TOKEN_SETTING => '',
        ];

        foreach ($settings as $name => $value) {
            SystemSetting::firstOrCreate([
                'name' => $name
            ], [
                'name' => $name,
                'value' => $value
            ]);
        }
    }
}
