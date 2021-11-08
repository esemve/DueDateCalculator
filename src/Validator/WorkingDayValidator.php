<?php

declare(strict_types = 1);

namespace Esemve\DueDateCalculator\Validator;

use \DateTimeInterface;
use Esemve\DueDateCalculator\Util\WorkingDaysConfiguration;

class WorkingDayValidator extends AbstractWorkingTimeValidator
{
    private WorkingDaysConfiguration $configuration;

    public function __construct(WorkingDaysConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function isValid(DateTimeInterface $dateTime): bool
    {
        return false === in_array($dateTime->format('w'), $this->configuration->getWeekendDays());
    }
}