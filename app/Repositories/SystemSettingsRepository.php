<?php

namespace App\Repositories;

use App\Models\SystemSetting;

class SystemSettingsRepository
{
    public static function getSetting($name) {
        $setting = SystemSetting::where('name', $name)->first();
        return $setting ? $setting->value : null;
    }

    public static function setSetting($name, $value) {
        SystemSetting::updateOrCreate(['name' => $name], [
            'value' => $value
        ]);
    }

    public static function getSettings() {
        return SystemSetting::all();
    }
}
