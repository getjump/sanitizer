<?php

namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorMessage;

class ObjectFormatter implements FormatterSanitizerInterface {
    /**
     * @var FormatterInterface[]
     */
    private $formatterArray = [];

    public function __construct($formatterArray = [])
    {
        $this->formatterArray = $formatterArray;
    }

    public function validate($input) : ValidationResultInterface
    {
        $validationResult = new ValidationResult();

        if (!is_object($input)) {
            $validationResult->addMessage(new ErrorMessage('Input is not an object'));
            $validationResult->setValid(false);
            return $validationResult;
        }

        foreach ($this->formatterArray as $key => $formatter) {
            $field = new BasicField($key);

            if (!property_exists($input, $key)) {
                $field->addMessage(new ErrorMessage('Field :field couldn\'t be found', [
                    ':field' => $key,
                ]));
                $field->setValid(false);

                $validationResult->addField($field);
                continue;
            }

            $validationResult->merge($formatter->validate($input->{$key}), $key);
        }

        return $validationResult;
    }

    public function sanitize($input)
    {
        $result = new \stdClass();

        foreach ($this->formatterArray as $key => $formatter) {
            if ($formatter instanceof FormatterSanitizerInterface) {
                $result->{$key} = $formatter->sanitize($input->{$key});
            }
        }

        return $result;
    }
}