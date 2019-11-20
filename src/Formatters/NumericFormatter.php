<?php


namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorMessage;

class NumericFormatter implements FormatterInterface
{
    public function validate($input) : ValidationResultInterface
    {
        $result = is_numeric($input);
        $validationResult = new ValidationResult();

        if ($result !== true) {
            $validationResult->addMessage(new ErrorMessage('Object is not of a numeric type'));
            $validationResult->setValid(false);

            return $validationResult;
        }

        return $validationResult;
    }
}
