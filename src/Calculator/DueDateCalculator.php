<?php

declare(strict_types=1);

namespace Esemve\DueDateCalculator\Calculator;

use DateTime;
use DateTimeInterface;
use Esemve\DueDateCalculator\Exception\InvalidSubmitDateException;
use Esemve\DueDateCalculator\Exception\InvalidTurnAroundHoursException;
use Esemve\DueDateCalculator\Util\DueDateCalculatorValidatorSet;
use Esemve\DueDateCalculator\Util\WorkingDaysConfiguration;

class DueDateCalculator
{
    private WorkingDaysConfiguration $configuration;

    private DueDateCalculatorValidatorSet $validatorSet;

    public function __construct(
        WorkingDaysConfiguration $configuration,
        DueDateCalculatorValidatorSet $validatorSet
    ) {
        $this->configuration = $configuration;
        $this->validatorSet = $validatorSet;
    }

    /**
     * Calculate the finish date for a task by submit date and working hours.
     *
     * @param DateTimeInterface $submitDate
     * @param int $turnAroundHours
     *
     * @return DateTime
     * @throws InvalidSubmitDateException
     * @throws InvalidTurnAroundHoursException
     */
    public function calculateDueDate(DateTimeInterface $submitDate, int $turnAroundHours): DateTime
    {
        $this->validatorSet->getSubmitDateValidator()->validate($submitDate);

        if ($turnAroundHours < 0) {
            throw new InvalidTurnAroundHoursException(sprintf('turnAroundHours %s is invalid! Must be a positive integer!', $turnAroundHours));
        }

        $outputDate = new DateTime($submitDate->format('Y-m-d H:i:s'), $submitDate->getTimezone());

        for ($i = 0; $i < $turnAroundHours; ++$i) {
            $this->calculateOneStep($outputDate);
        }

        return $outputDate;
    }

    private function calculateOneStep(DateTime $outputDate): void
    {
        $outputDate->modify('+1 hour');

        if (false === $this->validatorSet->getWorkingHourValidator()->isValid($outputDate)) {
            $this->resetHour($outputDate);
            $this->increaseDayForNextValid($outputDate);
        }
    }

    private function increaseDayForNextValid(DateTime $outputDate): void
    {
        do {
            $outputDate->modify('+1 day');
        } while (!$this->validatorSet->getWorkingDayValidator()->isValid($outputDate));
    }

    private function resetHour(DateTime $dateTime): void
    {
        $minutes = (int) $dateTime->format('i');
        $secs = (int) $dateTime->format('s');

        $newHour = $this->configuration->getStartTimeHour();
        if (0 === $minutes && 0 === $secs) {
            ++$newHour;
        }

        $dateTime->setTime(
            $newHour,
            $minutes,
            $secs
        );
    }
}
