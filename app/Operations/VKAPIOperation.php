<?php

namespace App\Operations;

use App\Models\SystemSetting;
use App\Repositories\SystemSettingsRepository;
use VK\Client\VKApiClient;

class VKAPIOperation
{
    public static function sendMessageToCommunityChat($message)
    {
        $vk_client = new VKApiClient();
        $vk_client->messages()->send(SystemSettingsRepository::getSetting(SystemSetting::VK_TOKEN_SETTING), [
            'peer_id' => 2000000000 + SystemSettingsRepository::getSetting(SystemSetting::COMMUNITY_CHAT_ID_SETTING),
//            'peer_id' => 286005561,
            'message' => $message,
            'random_id' => rand(10000, 1000000),
        ]);
    }
}
