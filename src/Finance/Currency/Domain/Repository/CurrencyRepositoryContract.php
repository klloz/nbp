<?php

namespace App\Finance\Currency\Domain\Repository;

use App\Finance\Currency\Domain\Model\Currency;

interface CurrencyRepositoryContract
{
    public function findByISO(mixed $code, ?\DateTimeInterface $date): ?Currency;

    public function store(Currency $model): self;

    public function flush(): self;
}
