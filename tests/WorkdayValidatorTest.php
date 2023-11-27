<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/WorkdayValidator.php';

class WorkdayValidatorTest extends PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider datesProvider
     * @param string $date Datum
     * @param bool $expected očekávaný výsledek
     */

    public function testValidWorkday(string $date, bool $expected)
    {
        $workdayValidator = new WorkdayValidator();

        $actual = $workdayValidator->validate($date);

        $this->assertTrue($expected, $actual);
    }

    public function datesProvider()
    {
        return [
            ['2023-11-27', true],
            ['2023-11-28', true],
            ['2023-11-29', true],
            ['2023-11-30', true],
            ['2023-12-01', true],
            ['2023-12-02', false],
            ['2023-12-03', false],
            ['neplatné datum', false],
        ];
    }
}
