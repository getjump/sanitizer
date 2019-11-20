<?php


namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorMessageInterface;

class BasicField implements FieldInterface
{
    /**
     * @var
     */
    private $key;

    /**
     * @var ErrorMessageInterface[]
     */
    private $messages = [];

    private $valid = true;

    public function __construct(?string $key, $valid = true, $messages = [])
    {
        $this->key = $key;
        $this->messages = $messages;
        $this->valid = $valid;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function setKey(string $key)
    {
        $this->key = $key;
    }

    public function addMessage(ErrorMessageInterface $message)
    {
        $this->messages[] = $message;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function setValid(bool $valid)
    {
        $this->valid = $valid;
    }
}