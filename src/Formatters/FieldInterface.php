<?php


namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorMessageInterface;

interface FieldInterface
{
    public function getKey(): string;
    public function setKey(string $key);

    public function addMessage(ErrorMessageInterface $message);
    public function getMessages(): array;

    public function isValid(): bool;
    public function setValid(bool $valid);
}