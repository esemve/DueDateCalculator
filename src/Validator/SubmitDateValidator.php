<?php

declare(strict_types = 1);

namespace Esemve\DueDateCalculator\Validator;

use \DateTimeInterface;

class SubmitDateValidator extends AbstractWorkingTimeValidator
{
    private AbstractWorkingTimeValidator $workingHourValidator;

    private AbstractWorkingTimeValidator $workingDayValidator;

    public function __construct(
        AbstractWorkingTimeValidator $workingHourValidator,
        AbstractWorkingTimeValidator $workingDayValidator
    )
    {
        $this->workingHourValidator = $workingHourValidator;
        $this->workingDayValidator = $workingDayValidator;
    }

    public function isValid(DateTimeInterface $dateTime): bool
    {
        return
            $this->isValidDate($dateTime) === true &&
            $this->workingDayValidator->isValid($dateTime) === true &&
            $this->workingHourValidator->isValid($dateTime) === true;
    }

    private function isValidDate(DateTimeInterface $dateTime): bool
    {
        return $dateTime->getTimestamp() > 0;
    }
}