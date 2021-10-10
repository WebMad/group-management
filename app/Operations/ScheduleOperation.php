<?php

namespace App\Operations;

use App\Models\Schedule;
use App\Models\SystemSetting;
use App\Repositories\SystemSettingsRepository;
use DateTime;

class ScheduleOperation
{
    /**
     * @param DateTime $date
     * @return array
     * @throws \Exception
     */
    public static function generateScheduleByDate(DateTime $date)
    {
        $start_year = new DateTime(SystemSettingsRepository::getSetting(SystemSetting::START_YEAR_SETTING));
        $counter_date = clone $start_year;

        $week = 1;

        while ($counter_date < $date) {
            $counter_date->modify('+1 day');
            if ($counter_date->format('w') == 1) {
                $week++;
            }
        }

        $schedule = Schedule::where('day_of_week', $date->format('w'))->with(['scheme' => function ($q) {
            $q->orderBy('start_time');
        }, 'subject', 'subject.teacher'])->get();

        $res = [];

        foreach ($schedule as $unit) {
            if (
                !empty($unit->start_week) && empty($unit->end_week) && $week < $unit->start_week
                || empty($unit->start_week) && !empty($unit->end_week) && $week > $unit->end_week
                || !empty($unit->start_week) && !empty($unit->end_week) && $week > $unit->end_week && $week < $unit->start_week
            ) {
                continue;
            }
            if ($unit->week_type == 0) {
                $res[] = $unit;
                continue;
            }
            if ($unit->week_type % 2 === $week % 2) {
                $res[] = $unit;
            }
        }

        return $res;
    }
}
