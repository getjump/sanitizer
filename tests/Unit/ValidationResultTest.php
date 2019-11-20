<?php

namespace getjump\Sanitizer\Tests\Unit;

use getjump\Sanitizer\Formatters\BasicField;
use getjump\Sanitizer\Formatters\ValidationResult;
use getjump\Sanitizer\Types\ErrorMessage;
use PHPUnit\Framework\TestCase;

class ValidationResultTest extends TestCase
{

    private function createValidationResult()
    {
        return new ValidationResult();
    }

    public function testAddGetMessages()
    {
        $validationResult = $this->createValidationResult();

        $error = new ErrorMessage('Test');

        $validationResult->addMessage($error);
        $this->assertContains($error, $validationResult->getMessages());
    }

    public function testGetErrors()
    {
        $validationResult = $this->createValidationResult();

        $error = new ErrorMessage('Test');

        $validationResult->addMessage($error);
        $this->assertContains($error, $validationResult->getErrors()->messages);
    }

    public function testSetMessages()
    {
        $validationResult = $this->createValidationResult();

        $error = new ErrorMessage('Test');

        $validationResult->setMessages([$error]);
        $this->assertEquals([$error], $validationResult->getMessages());
    }

    public function testAddGetFields()
    {
        $validationResult = $this->createValidationResult();

        $field = new BasicField("test");
        $validationResult->addField($field);

        $this->assertEquals($validationResult->getFields()['test'], $field);
    }

    public function testSetIsValid()
    {
        $validationResult = $this->createValidationResult();

        $validationResult->setValid(false);

        $this->assertFalse($validationResult->isValid());
    }

    public function testGetFieldsWithErrors()
    {
        $validationResult = $this->createValidationResult();

        $field = new BasicField("test", false);
        $validationResult->addField($field);

        $this->assertEquals($validationResult->getFields(), $validationResult->getFieldsWithErrors());
    }

    public function testMerge()
    {
        $validationResult = $this->createValidationResult();

        $field = new BasicField("test", true);
        $validationResult->addField($field);

        $innerValidationResult = $this->createValidationResult();

        $innerField = new BasicField("test", false);
        $innerField->addMessage(new ErrorMessage("test"));
        $innerValidationResult->addField($innerField);

        $validationResult->merge($innerValidationResult, "test");

        $this->assertEquals($validationResult->getFieldsWithErrors()["test.test"], $innerField);
    }
}
