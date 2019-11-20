<?php

namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorMessage;

class ArrayObject implements FormatterSanitizerInterface {
    /**
     * @var FormatterSanitizerInterface[]
     */
    private $formatterArray = [];

    public function __construct($formatterArray = [])
    {
        $this->formatterArray = $formatterArray;
    }

    public function validate($input) : ValidationResultInterface
    {
        $validationResult = new ValidationResult();
        $result = is_array($input);

        if ($result === false) {
            $validationResult->addMessage(new ErrorMessage('Input is not an array'));
            $validationResult->setValid(false);
            return $validationResult;
        }

        foreach ($this->formatterArray as $key => $formatter) {
            $field = new BasicField($key);

            if (!array_key_exists($key, $input)) {
                $field->addMessage(new ErrorMessage('Field couldn\'t be found'));
                $field->setValid(false);

                $validationResult->addField($field);

                continue;
            }

            if ($formatter instanceof FormatterInterface) {
                $validationResult->merge($formatter->validate($input[$key]), $key);
            }
        }

        return $validationResult;
    }

    public function sanitize($input)
    {
        $result = [];

        foreach ($this->formatterArray as $key => $sanitizer) {
            if ($sanitizer instanceof FormatterSanitizerInterface) {
                $result[$key] = $sanitizer->sanitize($input[$key]);
            }
        }
    }
}