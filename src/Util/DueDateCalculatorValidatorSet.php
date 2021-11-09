<?php

declare(strict_types=1);

namespace Esemve\DueDateCalculator\Util;

use Esemve\DueDateCalculator\Validator\AbstractWorkingTimeValidator;

class DueDateCalculatorValidatorSet
{
    private AbstractWorkingTimeValidator $workingDayValidator;

    private AbstractWorkingTimeValidator $workingHourValidator;

    private AbstractWorkingTimeValidator $submitDateValidator;

    public function __construct(
        AbstractWorkingTimeValidator $workingDayValidator,
        AbstractWorkingTimeValidator $workingHourValidator,
        AbstractWorkingTimeValidator $submitDateValidator
    ) {
        $this->workingDayValidator = $workingDayValidator;
        $this->workingHourValidator = $workingHourValidator;
        $this->submitDateValidator = $submitDateValidator;
    }

    public function getWorkingDayValidator(): AbstractWorkingTimeValidator
    {
        return $this->workingDayValidator;
    }

    public function getWorkingHourValidator(): AbstractWorkingTimeValidator
    {
        return $this->workingHourValidator;
    }

    public function getSubmitDateValidator(): AbstractWorkingTimeValidator
    {
        return $this->submitDateValidator;
    }
}
