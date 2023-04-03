<?php

namespace App\Common\Finance\Currency\Domain\Model;

use App\Finance\Currency\Domain\Repository\CurrencyRepositoryContract;

interface CurrencyContract
{
    public function update(CurrencyRepositoryContract $currencyRepository, string $name, float $exchangeRate): self;

    public static function fromRawData(CurrencyRepositoryContract $currencyRepository, array $rawData): self;
}
