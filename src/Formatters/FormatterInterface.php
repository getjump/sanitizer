<?php

namespace getjump\Sanitizer\Formatters;

interface FormatterInterface {
    public function validate($input) : ValidationResultInterface;
}