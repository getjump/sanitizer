<?php


namespace getjump\Sanitizer\Formatters;


use getjump\Sanitizer\Types\Error;
use getjump\Sanitizer\Types\ErrorCollection;
use getjump\Sanitizer\Types\ErrorMessageInterface;

class ValidationResult implements ValidationResultInterface
{
    /**
     * @var bool
     */
    private $valid = true;

    /**
     * @var array
     */
    private $messages = [];

    /**
     * @var FieldInterface[]
     */
    private $fields = [];

    /**
     * @var ErrorCollection
     */
    private $errorCollection;

    /**
     * @var FieldInterface[]
     */
    private $fieldsWithErrors = [];

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function addField(FieldInterface $field): void
    {
        if (!$field->isValid()) {
            $this->valid = false;
            $this->fieldsWithErrors[$field->getKey()] = $field;
        }

        $this->fields[$field->getKey()] = $field;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return FieldInterface[]
     */
    public function getFieldsWithErrors() {
        return $this->fieldsWithErrors;
    }

    public function getErrors(): ErrorCollection
    {
        $errorFields = $this->getFieldsWithErrors();

        $errorCollection = new ErrorCollection;

        $errorCollection->messages = $this->getMessages();

        foreach ($errorFields as $key => $errorField) {
            $error = new Error($errorField->getMessages());
            $errorCollection->errors[$key] = $error;
        }

        return $errorCollection;
    }

    public function setValid(bool $valid): void
    {
        $this->valid = $valid;
    }

    public function merge(ValidationResultInterface $validationResult, $key = null): void {
        $object = &$this->fields;

        $fields = $validationResult->getFields();

        $this->valid = $this->isValid() & $validationResult->isValid();

        $prefix = $key !== null ? $key . '.' : '';

        /**
         * @var $field FieldInterface
         */
        foreach ($fields as $innerKey => $field) {
            if (!$field->isValid() && count($field->getMessages()) > 0) {
                $this->fieldsWithErrors[$prefix . $innerKey] = $field;
            }

            $object[$prefix . $innerKey] = $field;
        }

        if (!array_key_exists($key, $object)) {
            $object[$key] = new BasicField($key, $validationResult->isValid(), $validationResult->getMessages());
        }
    }

    public function addMessage(ErrorMessageInterface $message): void
    {
        $this->messages[] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function setMessages(array $messages): void
    {
        $this->messages = $messages;
    }
}