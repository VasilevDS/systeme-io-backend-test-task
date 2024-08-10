<?php

declare(strict_types=1);

namespace App\Dto\Product;

use App\Dto\SuccessfulResponse;

final readonly class PriceResponse extends SuccessfulResponse
{
    public function __construct(public int $price)
    {
        parent::__construct();
    }
}
