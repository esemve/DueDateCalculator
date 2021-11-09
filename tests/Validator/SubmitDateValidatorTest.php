<?php

declare(strict_types=1);

namespace Esemve\Tets\DueDateCalculator\Validator;

use DateTime;
use Esemve\DueDateCalculator\Exception\InvalidSubmitDateException;
use Esemve\DueDateCalculator\Validator\AbstractWorkingTimeValidator;
use Esemve\DueDateCalculator\Validator\SubmitDateValidator;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
class SubmitDateValidatorTest extends TestCase
{
    public function testValidDate(): void
    {
        $date = new DateTime('2021-11-08 12:00:00');

        $validator = $this->createSubmitDateValidator(
            $this->createAbstractWorkingTimeValidator($date, true),
            $this->createAbstractWorkingTimeValidator($date, true),
        );

        self::assertTrue($validator->isValid($date));
    }

    public function testInvalidDate(): void
    {
        $validator = $this->createSubmitDateValidator(
            $this->createEmptyWorkingTimeValidator(),
            $this->createEmptyWorkingTimeValidator()
        );

        $invalidDate = new DateTime('0000-00-00 00:00:00');
        self::assertFalse($validator->isValid($invalidDate));

        self::expectException(InvalidSubmitDateException::class);
        $validator->validate($invalidDate);
    }

    public function testInvalidByExternalValidators(): void
    {
        $date = new DateTime('2021-11-08 12:00:00');

        $firstValidatorReturnFalseValidator = $this->createSubmitDateValidator(
            $this->createAbstractWorkingTimeValidator($date, false),
            $this->createAbstractWorkingTimeValidator($date, true),
        );

        $secondValidatorReturnFalseValidator = $this->createSubmitDateValidator(
            $this->createAbstractWorkingTimeValidator($date, true),
            $this->createAbstractWorkingTimeValidator($date, false),
        );

        $allValidatorReturnFalseValidator = $this->createSubmitDateValidator(
            $this->createAbstractWorkingTimeValidator($date, true),
            $this->createAbstractWorkingTimeValidator($date, false),
        );

        self::assertFalse($firstValidatorReturnFalseValidator->isValid($date));
        self::assertFalse($secondValidatorReturnFalseValidator->isValid($date));
        self::assertFalse($allValidatorReturnFalseValidator->isValid($date));
    }

    private function createAbstractWorkingTimeValidator(DateTime $expectedArgument, bool $response): AbstractWorkingTimeValidator
    {
        $mock = $this->createMock(AbstractWorkingTimeValidator::class);
        $mock->method('isValid')->with($expectedArgument)->willReturn($response);

        return $mock;
    }

    private function createEmptyWorkingTimeValidator(): AbstractWorkingTimeValidator
    {
        return $this->createMock(AbstractWorkingTimeValidator::class);
    }

    private function createSubmitDateValidator(AbstractWorkingTimeValidator $workingDayValidator, AbstractWorkingTimeValidator $workingHourValidator): SubmitDateValidator
    {
        return new SubmitDateValidator(
            $workingHourValidator,
            $workingDayValidator
        );
    }
}
