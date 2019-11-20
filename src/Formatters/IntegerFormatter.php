<?php


namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorMessage;

class IntegerFormatter extends NumericFormatter implements FormatterSanitizerInterface
{
    public function validate($input) : ValidationResultInterface
    {
        $validationResult = new ValidationResult();

        $result = is_numeric($input);

        if ($result !== true || fmod(floatval($input), 1) > 0) {
            $validationResult->addMessage(new ErrorMessage('Object is not of a integer type'));
            $validationResult->setValid(false);

            return $validationResult;
        }

        return $validationResult;
    }

    public function sanitize($input)
    {
        return intval($input);
    }
}
