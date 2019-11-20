<?php


namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorMessage;

/**
 * Class RussianFederalNumberFormatter
 * @package getjump\Sanitizer\Formatters
 * \+?([78])[\h]?[\(]?(\d{3})[\h]?[\)]?[\h]?(\d{3})[-]?[\h]?(\d{2})[\h]?[-]?(\d{2})
 * Accepts formats:
 * 88002320505 +79002320204, 8 800 555 35 35, 8 800 555-35-35, 8800 555-35-35, 8 (800) 555-35-35, 800 555 35 35
 * Could be done more effectively using normalisation, so remove excessive symbols like [-\h+] and etc
 * Also more heuristics likely is needed, like validating first digit +7 or 8, also 3 digits like 800 etc
 */
class RussianFederalNumberFormatter implements FormatterSanitizerInterface
{
    private $sanitizerFormat = '8 (:3dig1) :3dig2-:2dig1-:2dig2';

    public function __construct($sanitizerFormat = '8 (:3dig1) :3dig2-:2dig1-:2dig2')
    {
        $this->sanitizerFormat = $sanitizerFormat;
    }

    protected function executeRegExp($input): array {
        preg_match('/\+?([78])?[\h]?[\(]?(\d{3})[\h]?[\)]?[\h]?(\d{3})[-]?[\h]?(\d{2})[\h]?[-]?(\d{2})/', $input,$matches);

        return $matches;
    }

    public function validate($input): ValidationResultInterface
    {
        $stringFormatter = new StringFormatter();
        $validationResult = $stringFormatter->validate($input);

        if (!$validationResult->isValid()) {
            return $validationResult;
        }

        $matches = $this->executeRegExp($input);

        if (count($matches) < 5) {
            $validationResult->addMessage(new ErrorMessage('Phone :input number is incorrect', [
                ':input' => $input,
            ]));
            $validationResult->setValid(false);

            return $validationResult;
        }

        return $validationResult;
    }

    public function sanitize($input): string
    {
        $matches = $this->executeRegExp($input);

        if (count($matches) < 5) {
            return false;
        }

        return strtr($this->sanitizerFormat, [
            ':federalCode' => $matches[1],
            ':3dig1' => $matches[2],
            ':3dig2' => $matches[3],
            ':2dig1' => $matches[4],
            ':2dig2' => $matches[5]
        ]);
    }
}