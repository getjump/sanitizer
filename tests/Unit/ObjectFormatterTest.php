<?php

namespace getjump\Sanitizer\Tests\Unit;

use getjump\Sanitizer\Formatters\IntegerFormatter;
use getjump\Sanitizer\Formatters\ObjectFormatter;
use getjump\Sanitizer\Formatters\RussianFederalNumberFormatter;
use getjump\Sanitizer\Formatters\StringFormatter;
use PHPUnit\Framework\TestCase;

class ObjectFormatterTest extends TestCase
{

    public function testValidate()
    {
        $input = new \stdClass();

        $input->foo = 123;
        $input->bar = new \stdClass();
        $input->bar->test = "test";
        $input->baz = '8 (950) 288-56-23';

        $formatter = new ObjectFormatter([
            'foo' => new IntegerFormatter(),
            'bar' => new StringFormatter(),
            'baz' => new RussianFederalNumberFormatter(),
        ]);

        $this->assertFalse($formatter->validate($input)->isValid());
    }
}
