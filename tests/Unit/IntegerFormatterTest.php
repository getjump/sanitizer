<?php

namespace getjump\Sanitizer\Tests\Unit;

use getjump\Sanitizer\Formatters\IntegerFormatter;
use PHPUnit\Framework\TestCase;


class IntegerFormatterTest extends TestCase
{

    public function testValidate()
    {
        $inputsMap = [
            ['1', true], ['1000', true], ['-10', true],
            ['1.001', false], ['.001', false],
            ['test', false], ['0', true],
        ];

        foreach ($inputsMap as list($input, $output)) {
            $this->assertEquals((new IntegerFormatter)->validate($input)->isValid(), $output);
        }
    }
}
