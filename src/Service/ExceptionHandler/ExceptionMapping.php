<?php

namespace App\Service\ExceptionHandler;

class ExceptionMapping
{
    private int $code;

    private bool $hidden;

    private bool $loggable;

    public function __construct(int $code, bool $hidden = true, bool $loggable = false)
    {
        $this->code = $code;
        $this->hidden = $hidden;
        $this->loggable = $loggable;
    }

    public function getCode(): int
    {
        return $this->code;
    }

    public function isHidden(): bool
    {
        return $this->hidden;
    }

    public function isLoggable(): bool
    {
        return $this->loggable;
    }
}
