<?php


namespace getjump\Sanitizer\Types;


class ErrorMessage implements ErrorMessageInterface
{
    private $text;
    private $context = [];

    public function __construct(string $text, $context = []) {
        $this->text = $text;
        $this->context = $context;
    }

    public function getMessageText(): string
    {
        return $this->text;
    }

    public function getContext(): array
    {
        return $this->context;
    }
}