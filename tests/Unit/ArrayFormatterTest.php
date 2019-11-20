<?php

namespace getjump\Sanitizer\Tests\Unit;

use getjump\Sanitizer\Formatters\ArrayFormatter;
use getjump\Sanitizer\Formatters\IntegerFormatter;
use getjump\Sanitizer\Formatters\RussianFederalNumberFormatter;
use PHPUnit\Framework\TestCase;

class ArrayFormatterTest extends TestCase
{
    public function testValidation() {
        $inputsMap = [
            [[1, 2, 3], 'integer', true], [['1', '2', '3'], 'integer', false],
            [['88002320505', '8 (800) 555-35-35'], RussianFederalNumberFormatter::class, true]
        ];

        foreach ($inputsMap as list($input, $type, $output)) {
            $validationResult = (new ArrayFormatter($type))->validate($input);
            $this->assertEquals($validationResult->isValid(), $output);
        }
    }

    public function testSanitization() {
        $inputsMap = [
            [[1, 2, 3], IntegerFormatter::class, [1, 2, 3]], [['1', '2', '3'], IntegerFormatter::class, [1, 2, 3]],
            [['88002320505', '8 (800) 555-35-35'], RussianFederalNumberFormatter::class, ['8 (800) 232-05-05', '8 (800) 555-35-35']]
        ];

        foreach ($inputsMap as list($input, $type, $output)) {
            $validationResult = (new ArrayFormatter($type))->sanitize($input);
            $this->assertEquals($validationResult, $output);
        }
    }
}
