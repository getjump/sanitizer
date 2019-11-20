<?php

namespace getjump\Sanitizer\Formatters;

use getjump\Sanitizer\Types\ErrorCollection;
use getjump\Sanitizer\Types\ErrorMessageInterface;

interface ValidationResultInterface {
    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @param bool $valid
     */
    public function setValid(bool $valid): void;

    /**
     * @param FieldInterface $field
     * @return void
     */
    public function addField(FieldInterface $field): void;

    /**
     * @return ErrorCollection
     */
    public function getErrors() : ErrorCollection;

    /**
     * @return string[]
     */
    public function getMessages(): array;

    /**
     * @param ErrorMessageInterface $message
     * @return void
     */
    public function addMessage(ErrorMessageInterface $message): void;

    /**
     * @param ErrorMessageInterface[] $messages
     * @return void
     */
    public function setMessages(array $messages): void;

    /**
     * @return FieldInterface[]
     */
    public function getFields(): array;

    /**
     * @param ValidationResultInterface $validationResult
     * @param null $key
     * @return void
     */
    public function merge(ValidationResultInterface $validationResult, $key = null): void;
}