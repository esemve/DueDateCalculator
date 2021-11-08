<?php

declare(strict_types = 1);

namespace Esemve\DueDateCalculator\Validator;

use \DateTimeInterface;
use Esemve\DueDateCalculator\Util\WorkingDaysConfiguration;

class WorkingHourValidator extends AbstractWorkingTimeValidator
{
    private WorkingDaysConfiguration $configuration;

    public function __construct(WorkingDaysConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function isValid(DateTimeInterface $dateTime): bool
    {
        return $this->isHourBetweenValidHours($dateTime) || $this->isHourEdgeWithoutMinutesAndSecs($dateTime);
    }

    private function isHourBetweenValidHours(DateTimeInterface $dateTime): bool
    {
        $checkHour = (int) $dateTime->format('G');

        return $checkHour < $this->configuration->getEndTimeHour() && $checkHour >= $this->configuration->getStartTimeHour();
    }

    private function isHourEdgeWithoutMinutesAndSecs(DateTimeInterface $dateTime): bool
    {
        return (int) $dateTime->format('G') === $this->configuration->getEndTimeHour() &&
            $dateTime->format('i') === '00' &&
            $dateTime->format('s') === '00';
    }

}