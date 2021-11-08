<?php

declare(strict_types = 1);

namespace Esemve\DueDateCalculator\Util;

class WorkingDaysConfiguration
{
    /**
     * Set weekend days for datetemime "w" (day of week) check
     * Starting from 0 (sunday)
     */
    private const WEEKEND_DAYS = [
        0,
        6
    ];

    /**
     * Work starts from
     */
    private const START_TIME_HOUR = 9;

    /**
     * Work stops at
     */
    private const END_TIME_HOUR = 17;

    public function getWeekendDays(): array
    {
        return self::WEEKEND_DAYS;
    }

    public function getStartTimeHour(): int
    {
        return self::START_TIME_HOUR;
    }

    public function getEndTimeHour(): int
    {
        return self::END_TIME_HOUR;
    }
}