<?php


namespace getjump\Sanitizer\Formatters;


class ChainFormatter implements FormatterInterface
{
    /**
     * @var FormatterInterface[]
     */
    private $formatterArray = [];

    private $failOnFirst = false;

    public function __construct(...$formatters)
    {
        $this->formatterArray = $formatters;
    }

    public function setFailOnFirst($failOnFirst) {
        $this->failOnFirst = $failOnFirst;
        return $this;
    }

    public function validate($input): ValidationResultInterface
    {
        $validationResult = new ValidationResult();

        foreach ($this->formatterArray as $formatter) {
            $innerValidationResult = $formatter->validate($input);

            $validationResult->merge($innerValidationResult);

            if ($this->failOnFirst && !$innerValidationResult->isValid()) {
                return $validationResult;
            }
        }

        return $validationResult;
    }
}