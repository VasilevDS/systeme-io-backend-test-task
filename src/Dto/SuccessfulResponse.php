<?php

declare(strict_types=1);

namespace App\Dto;

readonly class SuccessfulResponse
{
    public function __construct(public string $status = 'ok')
    {}
}
