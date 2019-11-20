<?php

namespace getjump\Sanitizer\Tests\Unit;

use getjump\Sanitizer\Formatters\ArrayObject;
use getjump\Sanitizer\Formatters\StringFormatter;
use PHPUnit\Framework\TestCase;

class ArrayObjectTest extends TestCase
{
    public function testValidateIsNotArray()
    {
        $formatter = new ArrayObject([]);
        $input = false;
        $this->assertFalse($formatter->validate($input)->isValid());
    }

    public function testValidateIsArrayNested()
    {
        $formatter = new ArrayObject([
            new ArrayObject([
                new ArrayObject([
                    new StringFormatter()
                ])
            ])
        ]);
        $input = [
            [
                [
                    'test'
                ]
            ]
        ];
        $this->assertTrue($formatter->validate($input)->isValid());
    }

    public function testValidateIsArrayWithWrongElementNested()
    {
        $formatter =
            new ArrayObject([
                'x' => new ArrayObject([
                    'y' => new ArrayObject([
                        'z' => new StringFormatter()
                    ])
                ])
        ]);
        $input = [
            'x' => [
                'y' => [
                    'z' => false
                ]
            ]
        ];
        $this->assertFalse($formatter->validate($input)->isValid());
    }

    public function testValidateIsNotArrayNested()
    {
        $formatter = new ArrayObject([
            new ArrayObject([])
        ]);
        $input = [
            false
        ];
        $this->assertFalse($formatter->validate($input)->isValid());
    }

    public function testValidationMessagesKeyDoesNotExist()
    {
        $formatter = new ArrayObject([
            'data' => new ArrayObject([
                'testString' => new StringFormatter()
            ]),
        ]);

        $input = [];

        $validation = $formatter->validate($input);

        $this->assertFalse($validation->isValid());
        $this->assertEquals($validation->getErrors()->errors['data']->messages[0]->getMessageText(), 'Field couldn\'t be found');
    }

    public function testValidate()
    {
        $formatter = new ArrayObject([
            'data' => new ArrayObject([
                'testString' => new StringFormatter()
            ]),
        ]);

        $input = [
            'data' => [
                'testString' => 'string'
            ]
        ];

        $validation = $formatter->validate($input);

        $this->assertTrue($validation->isValid());
    }
}
