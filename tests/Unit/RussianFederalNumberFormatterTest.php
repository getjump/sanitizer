<?php

namespace getjump\Sanitizer\Tests\Unit;

use getjump\Sanitizer\Formatters\RussianFederalNumberFormatter;
use PHPUnit\Framework\TestCase;

class RussianFederalNumberFormatterTest extends TestCase
{
    public function testPhoneNumberValidation() {
        $inputsMap = [
            ['88002320505', true], ['+79002320204', true], ['8 800 555 35 35', true], ['8 800 555-35-35', true],
            ['8800 555-35-35', true], ['8 (800) 555-35-35', true], ['800 555 35 35', true],
            [false, false], ['20000000', false], ['+7228228', false], ['-1', false]
        ];

        foreach ($inputsMap as list($input, $output)) {
            $validationResult = (new RussianFederalNumberFormatter())->validate($input);
            $this->assertEquals($validationResult->isValid(), $output);
        }
    }

    public function testPhoneNumberSanitization() {
        $inputsMap = [
            ['88002320505', '8 (800) 232-05-05'], ['+79002320204', '8 (900) 232-02-04'], ['8 800 555 35 35', '8 (800) 555-35-35'], ['8 800 555-35-35', '8 (800) 555-35-35'],
            ['8800 555-35-35', '8 (800) 555-35-35'], ['8 (800) 555-35-35', '8 (800) 555-35-35'], ['800 555 35 35', '8 (800) 555-35-35'],
            [false, false], ['20000000', false], ['+7228228', false], ['-1', false]
        ];

        foreach ($inputsMap as list($input, $output)) {
            $validationResult = (new RussianFederalNumberFormatter())->sanitize($input);
            $this->assertEquals($validationResult, $output);
        }
    }
}
