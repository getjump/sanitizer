<?php


namespace getjump\Sanitizer\Types;


interface ErrorMessageInterface
{
    public function getMessageText(): string;
    public function getContext(): array;
}