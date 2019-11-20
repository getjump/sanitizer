<?php

namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorMessage;

class JsonObject implements FormatterInterface {
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
        $result = json_decode($input);

        if ($result === NULL) {
            $validationResult->addMessage(new ErrorMessage('Can\'t decode a JSON'));
            $validationResult->setValid(false);
            return $validationResult;
        }

        $objectValidation = new ObjectFormatter($this->formatterArray);

        $validationResult->merge($objectValidation->validate($result));

        return $validationResult;
    }

    public function sanitize($input)
    {
        $objectFormatter = new ObjectFormatter($this->formatterArray);
        return $objectFormatter->sanitize(json_decode($input));
    }
}