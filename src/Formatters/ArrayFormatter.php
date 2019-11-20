<?php


namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorMessage;

class ArrayFormatter implements FormatterSanitizerInterface
{
    private $type;

    public function __construct($type)
    {
        $this->type = $type;
    }

    public function validate($input): ValidationResultInterface
    {
        $validationResult = new ValidationResult();
        $result = is_array($input);

        if ($result === false) {
            $validationResult->addMessage(new ErrorMessage('Input is not an array'));
            $validationResult->setValid(false);
            return $validationResult;
        }

        $formatter = $this->getFormatterForType($this->type);

        foreach ((array) $input as $item) {
            if ($formatter !== null) {
                $innerValidationResult = $formatter->validate($item);
                $validationResult->merge($innerValidationResult);

                if (!$validationResult->isValid()) {
                    return $validationResult;
                }

                continue;
            }

            if ($this->type !== $this->getTypeOrClassName($item)) {
                $validationResult->addMessage(new ErrorMessage('Item has incompatible type'));
                $validationResult->setValid(false);

                return $validationResult;
            }
        }

        return $validationResult;
    }

    private function getTypeOrClassName($input): string {
        $typeName = gettype($input);

        if ($typeName === 'object') {
            $typeName = get_class($typeName);
        }

        return $typeName;
    }

    private function getFormatterForType($type): ?FormatterSanitizerInterface {
        $formatter = null;

        if (is_subclass_of($type, FormatterSanitizerInterface::class)) {
            $formatter = new $type;
        }

        return $formatter;
    }

    public function sanitize($input)
    {
        $result = [];
        $formatter = $this->getFormatterForType($this->type);
        if ($formatter !== null) {
            foreach ($input as $element) {
                $result[] = $formatter->sanitize($element);
            }
        } else {
            $result = $input;
        }

        return $result;
    }
}