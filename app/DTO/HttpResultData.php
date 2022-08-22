<?php

namespace App\DTO;

class HttpResultData
{
    public function __construct(private bool $status, private array $content)
    {
    }

    public function isSuccess(): bool
    {
        return $this->status;
    }

    public function getContent(): array
    {
        return $this->content;
    }
}
