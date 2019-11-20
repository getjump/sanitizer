<?php

namespace getjump\Sanitizer\Tests\Unit;

use getjump\Sanitizer\Formatters\StringFormatter;

use PHPUnit\Framework\TestCase;

class StringFormatterTest extends TestCase
{
    public function generateInput($value) {
        $formatter = new StringFormatter();

        return $formatter->validate($value)->isValid();
    }

    public function testArray() {
        $this->assertTrue(!$this->generateInput(
            ['data']
        ));
    }

    public function testValidate()
    {
        $this->assertTrue($this->generateInput(
            'Test input'
        ));
    }

    public function testMinLength()
    {
        $this->assertTrue((new StringFormatter(5))->validate('Tests')->isValid());
        $this->assertFalse((new StringFormatter(5))->validate('Test')->isValid());
    }

    public function testMaxLength()
    {
        $this->assertTrue((new StringFormatter(0, 6))->validate('Tests')->isValid());
        $this->assertFalse((new StringFormatter(0, 3))->validate('Test')->isValid());
    }
}
