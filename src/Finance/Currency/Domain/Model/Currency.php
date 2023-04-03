<?php

namespace App\Finance\Currency\Domain\Model;

use App\Common\Finance\Currency\Domain\Model\CurrencyContract;
use App\Finance\Currency\Domain\Repository\CurrencyRepositoryContract;

class Currency implements CurrencyContract
{

    public static function fromRawData(CurrencyRepositoryContract $currencyRepository, mixed $rawData): self
    {
        // TODO: Implement update() method.
    }

    public function update(
        CurrencyRepositoryContract $currencyRepository,
        string $name,
        float $exchangeRate
    ): self {
        // TODO: Implement update() method.
    }
}
