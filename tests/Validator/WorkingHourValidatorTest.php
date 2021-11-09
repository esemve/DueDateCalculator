<?php

declare(strict_types=1);

namespace Esemve\Tets\DueDateCalculator\Validator;

use DateTime;
use Esemve\DueDateCalculator\Util\WorkingDaysConfiguration;
use Esemve\DueDateCalculator\Validator\WorkingHourValidator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class WorkingHourValidatorTest extends TestCase
{
    /**
     * @dataProvider dataProvider
     *
     * @throws \Exception
     */
    public function testWorkingHourValidator(int $startHour, int $endHour, string $check, bool $expectedOutput): void
    {
        $configration = $this->createMockWorkingDaysConfiguration($startHour, $endHour);
        $validator = $this->createWorkingHourValidator($configration);

        $testDate = new DateTime(sprintf('2021-01-01 %s', $check));

        self::assertEquals($expectedOutput, $validator->isValid($testDate));
    }

    public function dataProvider(): array
    {
        return [
            [7, 13, '11:00:00', true],
            [7, 13, '13:00:01', false],
            [7, 13, '13:01:00', false],
            [7, 14, '13:01:01', true],
            [7, 14, '06:00:00', false],
            [7, 14, '07:00:00', true],
            [0, 23, '07:00:00', true],
            [0, 23, '01:21:11', true],
            [11, 12, '11:00:00', true],
            [11, 12, '12:00:00', true],
        ];
    }

    private function createMockWorkingDaysConfiguration(int $startHour, int $endHour): WorkingDaysConfiguration
    {
        $mock = $this->createMock(WorkingDaysConfiguration::class);
        $mock->method('getStartTimeHour')->willReturn($startHour);
        $mock->method('getEndTimeHour')->willReturn($endHour);

        return $mock;
    }

    private function createWorkingHourValidator(WorkingDaysConfiguration $workingDaysConfiguration): WorkingHourValidator
    {
        return new WorkingHourValidator($workingDaysConfiguration);
    }
}
