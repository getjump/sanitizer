<?php

namespace getjump\Sanitizer\Tests\Unit;

use getjump\Sanitizer\Formatters\FloatFormatter;
use PHPUnit\Framework\TestCase;

class FloatFormatterTest extends TestCase
{

    public function testValidate()
    {
        $inputsMap = [
            ['1', false], ['1000', false], ['-10', false],
            ['1.001', true], ['.001', true],
            ['test', false], ['0', false],
            [1, false], [1.001, true], [.001, true]
        ];

        foreach ($inputsMap as list($input, $output)) {
            $this->assertEquals((new FloatFormatter())->validate($input)->isValid(), $output);
        }
    }
}
