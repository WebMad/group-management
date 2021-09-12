<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\SystemSetting;
use App\Operations\ScheduleOperation;
use App\Operations\VKAPIOperation;
use App\Repositories\SystemSettingsRepository;
use DateTime;
use Illuminate\Http\Request;
use VK\Client\VKApiClient;

class VKAPIController extends Controller
{
    public function route(Request $request)
    {
        switch ($request->input('type')) {
            case 'confirmation':
                return SystemSettingsRepository::getSetting(SystemSetting::VK_CONFIRMATION_STRING_SETTING);
        }
    }

    public function sendMessage()
    {
        VKAPIOperation::sendMessageToCommunityChat('Тест');
    }

    public function sendSchedule()
    {
        /** @var Schedule[] $schedule */
        $schedule = ScheduleOperation::generateScheduleByDate(new DateTime());

        $message = "Расписание на сегодня:\n\n";

        foreach ($schedule as $unit) {
            $start_time = (new DateTime($unit->scheme->start_time))->format('H:i');
            $end_time = (new DateTime($unit->scheme->end_time))->format('H:i');
            $message .= " - {$start_time}-{$end_time} {$unit->subject->name} \n";
        }

        VKAPIOperation::sendMessageToCommunityChat($message);
    }
}
