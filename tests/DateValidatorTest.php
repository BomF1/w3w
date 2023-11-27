<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/DateValidator.php';

class DateValidatorTest extends PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider datesProvider
     * @param string $date Datum
     * @param bool $expected očekávaný výsledek
     */
    public function testValidDate(string $date,bool $expected)
    {
        $dateValidator = new DateValidator();

        $actual = $dateValidator->validate($date);

        $this->assertTrue($expected, $actual);
    }

    public function datesProvider()
    {
        return [
            ['11. 1. 2023', true],
            ['20. 20. 2023', false],
            ['11.5.2023', true],
            [' 11 . 12 . 2023 ', true],
            [' 11 . 03 . 2023 ', true],
            [' 11.01.2023 ', true],
        ];
    }
}
