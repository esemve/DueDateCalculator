<?php

declare(strict_types=1);

namespace Esemve\DueDateCalculator\Validator;

use DateTimeInterface;
use Esemve\DueDateCalculator\Exception\InvalidSubmitDateException;

abstract class AbstractWorkingTimeValidator
{
    abstract public function isValid(DateTimeInterface $dateTime): bool;

    public function validate(DateTimeInterface $dateTime): void
    {
        if (!$this->isValid($dateTime)) {
            throw new InvalidSubmitDateException(
                sprintf('[%s] Invalid submit date: %s', get_class($this), $dateTime->format('Y-m-d H:i:s'))
            );
        }
    }
}
