<?php


namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorMessage;

class FloatFormatter implements FormatterSanitizerInterface
{
    public function validate($input) : ValidationResultInterface
    {
        $validationResult = new ValidationResult();

        $result = is_numeric($input);

        if ($result !== true || !(fmod(floatval($input), 1) > 0)) {
            $validationResult->addMessage(new ErrorMessage('Object is not of a float type'));
            $validationResult->setValid(false);

            return $validationResult;
        }

        return $validationResult;
    }

    public function sanitize($input)
    {
        return floatval($input);
    }
}
