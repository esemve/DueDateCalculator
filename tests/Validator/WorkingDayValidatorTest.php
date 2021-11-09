<?php

declare(strict_types=1);

namespace Esemve\Tets\DueDateCalculator\Validator;

use DateTime;
use Esemve\DueDateCalculator\Util\WorkingDaysConfiguration;
use Esemve\DueDateCalculator\Validator\WorkingDayValidator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class WorkingDayValidatorTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     */
    public function testWorkingDayValidator(array $weekendDays, DateTime $testDate, bool $expectedOutput): void
    {
        $configration = $this->createMockWorkingDaysConfiguration($weekendDays);
        $validator = $this->createWorkingDayValidator($configration);

        self::assertEquals($expectedOutput, $validator->isValid($testDate));
    }

    public function dataProvider(): array
    {
        return [
            [
                [],
                new DateTime('2021-11-13 10:00:00'),
                true,
            ],
            [
                [],
                new DateTime('2021-11-14 10:00:00'),
                true,
            ],
            [
                [],
                new DateTime('2021-11-10 10:00:00'),
                true,
            ],
            [
                [0, 6],
                new DateTime('2021-11-13 10:00:00'),
                false,
            ],
            [
                [0, 6],
                new DateTime('2021-11-14 10:00:00'),
                false,
            ],
            [
                [0, 6],
                new DateTime('2021-11-10 10:00:00'),
                true,
            ],
        ];
    }

    private function createMockWorkingDaysConfiguration(array $weekendDays): WorkingDaysConfiguration
    {
        $mock = $this->createMock(WorkingDaysConfiguration::class);
        $mock->expects(self::once())->method('getWeekendDays')->willReturn($weekendDays);

        return $mock;
    }

    private function createWorkingDayValidator(WorkingDaysConfiguration $workingDaysConfiguration): WorkingDayValidator
    {
        return new WorkingDayValidator($workingDaysConfiguration);
    }
}
