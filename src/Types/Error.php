<?php


namespace getjump\Sanitizer\Types;


class Error
{
    /**
     * @var ErrorMessageInterface[]
     */
    public $messages = [];

    public function __construct(array $messages = [])
    {
        $this->messages = $messages;
    }
}