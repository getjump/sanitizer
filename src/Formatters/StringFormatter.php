<?php


namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorMessage;

class StringFormatter implements FormatterSanitizerInterface
{
    private $minLength = null;
    private $maxLength = null;

    public function __construct($minLength = null, $maxLength = null)
    {
        $this->maxLength = $maxLength;
        $this->minLength = $minLength;
    }

    public function validate($input) : ValidationResultInterface
    {
        $result = is_string($input);
        $validationResult = new ValidationResult();

        if (!$result) {
            $validationResult->addMessage(new ErrorMessage('Object not a string'));
            $validationResult->setValid(false);

            return $validationResult;
        }

        $this->validateLength($input, $validationResult);

        return $validationResult;
    }

    private function validateLength(string $input, ValidationResultInterface &$validationResult) : void
    {
        $stringLength = strlen($input);

        if ($this->minLength !== null && $stringLength < $this->minLength) {
            $validationResult->addMessage(new ErrorMessage('String length :length is less then minimal amount :min', [
                ':length' => $stringLength,
                ':min' => $this->minLength,
            ]));
            $validationResult->setValid(false);
        }

        if ($this->maxLength !== null && $stringLength > $this->maxLength) {
            $validationResult->addMessage(new ErrorMessage('String length :length is over maximal amount :max', [
                ':length' => $stringLength,
                ':max' => $this->maxLength,
            ]));
            $validationResult->setValid(false);
        }
    }

    public function sanitize($input) : string
    {
        return (string) $input;
    }
}