<?php

declare(strict_types=1);

namespace Esemve\Tests\DueDateCalculator\Calculator;

use DateTime;
use Esemve\DueDateCalculator\Calculator\DueDateCalculator;
use Esemve\DueDateCalculator\Exception\InvalidSubmitDateException;
use Esemve\DueDateCalculator\Exception\InvalidTurnAroundHoursException;
use Esemve\DueDateCalculator\Util\DueDateCalculatorValidatorSet;
use Esemve\DueDateCalculator\Util\WorkingDaysConfiguration;
use Esemve\DueDateCalculator\Validator\SubmitDateValidator;
use Esemve\DueDateCalculator\Validator\WorkingDayValidator;
use Esemve\DueDateCalculator\Validator\WorkingHourValidator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class DueDateCalculatorTest extends TestCase
{
    /**
     * @dataProvider validDataProvider
     *
     * @throws InvalidSubmitDateException
     * @throws InvalidTurnAroundHoursException
     */
    public function testCalculateDueDate(string $submitDateString, int $turnAroundHours, string $expectedOutputString): void
    {
        $submitDate = $this->createDateTimeFromString($submitDateString);
        $expectedOutput = $this->createDateTimeFromString($expectedOutputString);

        $calculator = $this->createCalculator();
        $output = $calculator->calculateDueDate($submitDate, $turnAroundHours);

        self::assertEquals($expectedOutput->format('Y-m-d H:i:s'), $output->format('Y-m-d H:i:s'));
    }

    public function validDataProvider(): array
    {
        return [
            ['2021-11-08 09:00:00', 1, '2021-11-08 10:00:00'],
            ['2021-11-08 09:00:00', 9, '2021-11-09 10:00:00'],
            ['2021-11-08 09:00:00', 8, '2021-11-08 17:00:00'],
            ['2021-11-08 09:00:00', 40, '2021-11-12 17:00:00'],
            ['2021-11-08 09:00:00', 41, '2021-11-15 10:00:00'],
            ['2021-11-08 09:00:00', 81, '2021-11-22 10:00:00'],
            ['2021-11-08 09:01:00', 8, '2021-11-09 09:01:00'],
            ['2021-11-08 09:01:00', 2, '2021-11-08 11:01:00'],
            ['2021-11-08 09:01:00', 2, '2021-11-08 11:01:00'],
            ['1996-02-29 13:01:11', 2, '1996-02-29 15:01:11'],
            ['1996-02-28 16:01:11', 2, '1996-02-29 10:01:11'],
            ['2001-02-28 16:01:11', 2, '2001-03-01 10:01:11'],
            ['2001-02-28 16:01:11', 0, '2001-02-28 16:01:11'],
        ];
    }

    /**
     * @dataProvider invalidSubmitDateDataProvider
     *
     * @throws InvalidSubmitDateException
     * @throws InvalidTurnAroundHoursException
     */
    public function testInvalidSubmitDateForCalculateDueDate(string $submitDateString): void
    {
        $submitDate = $this->createDateTimeFromString($submitDateString);

        self::expectException(InvalidSubmitDateException::class);

        $calculator = $this->createCalculator();
        $calculator->calculateDueDate($submitDate, 5);
    }

    public function invalidSubmitDateDataProvider(): array
    {
        return [
            ['2021-11-08 06:00:00'],
            ['2021-11-08 19:00:00'],
            ['2021-11-13 11:00:00'],
            ['2021-11-14 11:00:00'],
            ['0000-00-00 12:00:00'],
        ];
    }

    public function testCalculateDueDateByDateTimeImmutable(): void
    {
        $submitDate = new \DateTimeImmutable('2021-11-08 12:00:01');
        $expectedOutput = $this->createDateTimeFromString('2021-11-08 13:00:01');

        $calculator = $this->createCalculator();
        $output = $calculator->calculateDueDate($submitDate, 1);

        self::assertEquals($expectedOutput->format('Y-m-d H:i:s'), $output->format('Y-m-d H:i:s'));
    }

    public function testInvalidTurnAroundHoursForCalculateDueDate(): void
    {
        self::expectException(InvalidTurnAroundHoursException::class);
        $calculator = $this->createCalculator();
        $calculator->calculateDueDate(new DateTime('2021-11-08 13:11:00'), -4);
    }

    private function createDateTimeFromString(string $dateTime): DateTime
    {
        return new DateTime($dateTime);
    }

    private function createCalculator(): DueDateCalculator
    {
        return new DueDateCalculator(
            $this->createConfiguration(),
            $this->createDueDateCalculatorValidatorSet(),
        );
    }

    private function createWorkingDayValidator(): WorkingDayValidator
    {
        return new WorkingDayValidator($this->createConfiguration());
    }

    private function createWorkingHourValidator(): WorkingHourValidator
    {
        return new WorkingHourValidator($this->createConfiguration());
    }

    private function createSubmitDateValidator(): SubmitDateValidator
    {
        return new SubmitDateValidator(
            $this->createWorkingHourValidator(),
            $this->createWorkingDayValidator()
        );
    }

    private function createDueDateCalculatorValidatorSet(): DueDateCalculatorValidatorSet
    {
        return new DueDateCalculatorValidatorSet(
            $this->createWorkingDayValidator(),
            $this->createWorkingHourValidator(),
            $this->createSubmitDateValidator()
        );
    }

    private function createConfiguration(): WorkingDaysConfiguration
    {
        return new WorkingDaysConfiguration();
    }
}
