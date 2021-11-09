# Due Date Calculator

Test for Emarsys

## What is this?
This is a simple calculator library which can calculate the finish date for task by starting date and turn around hours. 
The calculator counts on weekends and working hours.

## Requirement
- php7.4 (or newer)
- installed composer

## Installation
Pull this repository and run this command:
```
composer up
```

## Run tests
If you have already installed phpunit in the root directory simply run it:
```
phpunit
```

If you don't have globally installed phpunit after the composer up you can run the tests from the root directory with:
```
./vendor/bin/phpunit
```

## Usage

After installation you can use the `Esemve\DueDateCalculator\Calculator\DueDateCalculator` class. This contains one public method:

```
public function calculateDueDate(DateTimeInterface $submitDate, int $turnAroundHours): DateTime
```

#### Parameters:

**$submitDate** *(DateTimeInterface)*

Start datetime for the calculation. This can be any DateTimeInterface (DateTime, DateTimeImmutable etc).

**$turnAroundHours** *(int)*

How many hours does the task take. This must be a >0 integer.

#### Return:

**DateTime** 

The expected finish date for the task.

#### Throws:
**InvalidSubmitDateException**

The submit date (start date) does not contain a valid working day or hour.

**InvalidTurnAroundHoursException**

The turn around hours is not a positive integer.
 
