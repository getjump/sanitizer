<?php

namespace getjump\Sanitizer\Tests\Unit;

use getjump\Sanitizer\Formatters\IntegerFormatter;
use getjump\Sanitizer\Formatters\JsonObject;
use getjump\Sanitizer\Formatters\ObjectFormatter;
use getjump\Sanitizer\Formatters\RussianFederalNumberFormatter;
use getjump\Sanitizer\Formatters\StringFormatter;
use PHPUnit\Framework\TestCase;

class JsonObjectTest extends TestCase
{

    public function testValidate()
    {
        $input = '{"foo": "123", "bar": "asd", "baz": "8 (950) 288-56-23"}';

        $formatter = new JsonObject([
            'foo' => new IntegerFormatter(),
            'bar' => new StringFormatter(),
            'baz' => new RussianFederalNumberFormatter(),
        ]);

        $this->assertTrue($formatter->validate($input)->isValid());
    }

    public function testValidateNested()
    {
        $input = '{"foo": "123", "bar": {"test": "test"}, "baz": "8 (950) 288-56-23"}';

        $formatter = new JsonObject([
            'foo' => new IntegerFormatter(),
            'bar' => new ObjectFormatter([
                'test' => new StringFormatter()
            ]),
            'baz' => new StringFormatter(),
        ]);

        $this->assertTrue($formatter->validate($input)->isValid());
    }

    public function testSanitization()
    {
        $input = '{"foo": "123", "bar": "asd", "baz": "8 (950) 288-56-23"}';

        $formatter = new JsonObject([
            'foo' => new IntegerFormatter(),
            'bar' => new StringFormatter(),
            'baz' => new RussianFederalNumberFormatter(),
        ]);

        $stdClass = new \stdClass();

        $stdClass->foo = 123;
        $stdClass->bar = "asd";
        $stdClass->baz = '8 (950) 288-56-23';

        $this->assertEquals($formatter->sanitize($input), $stdClass);
    }

    public function testSanitizationBad()
    {
        $input = '{"foo": "123\'DROP TABLE;", "bar": {"test": "test", "testw": {"test": "test"}}, "baz": "8 (950) 288-56-23"}';

        $formatter = new JsonObject([
            'foo' => new IntegerFormatter(),
            'bar' => new ObjectFormatter([
                'test' => new StringFormatter()
            ]),
            'baz' => new StringFormatter(),
        ]);

        $stdClass = new \stdClass();

        $stdClass->foo = 123;
        $stdClass->bar = new \stdClass();
        $stdClass->bar->test = "test";
        $stdClass->baz = '8 (950) 288-56-23';

        $this->assertEquals($formatter->sanitize($input), $stdClass);
    }

    public function testSanitizationNested()
    {
        $input = '{"foo": "123", "bar": {"test": "test"}, "baz": "8 (950) 288-56-23"}';

        $formatter = new JsonObject([
            'foo' => new IntegerFormatter(),
            'bar' => new ObjectFormatter([
                'test' => new StringFormatter()
            ]),
            'baz' => new StringFormatter(),
        ]);

        $stdClass = new \stdClass();

        $stdClass->foo = 123;
        $stdClass->bar = new \stdClass();
        $stdClass->bar->test = "test";
        $stdClass->baz = '8 (950) 288-56-23';

        $this->assertEquals($formatter->sanitize($input), $stdClass);
    }
}
