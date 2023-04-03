<?php

namespace App\Common\Finance\Currency\Domain\Model;

use App\Finance\Currency\Domain\Repository\CurrencyRepositoryContract;
use Symfony\Component\Uid\Uuid;

interface CurrencyContract
{
    public static function fromRawData(CurrencyRepositoryContract $currencyRepository, array $rawData): self;

    public function update(CurrencyRepositoryContract $currencyRepository, string $name, float $exchangeRate): self;

    public function id(): Uuid;

    public function name(): string;

    public function currencyCode(): string;

    public function exchangeRate(): int;

    public function date(): \DateTimeInterface;
}
