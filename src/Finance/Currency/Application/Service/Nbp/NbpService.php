<?php

namespace App\Finance\Currency\Application\Service\Nbp;

use App\Finance\Currency\Domain\Model\Currency;
use App\Finance\Currency\Domain\Repository\CurrencyRepositoryContract;

readonly class NbpService
{
    public const DEFAULT_TABLE = 'a';

    public function __construct(private NbpClient $nbpClient, private CurrencyRepositoryContract $currencyRepository)
    {
    }

    public function loadExchangeRates(?\DateTimeInterface $date = null): void
    {
        $date = $date ?? new \DateTimeImmutable();
        try {
            $ratesRaw = $this->nbpClient->getExchangeRates(self::DEFAULT_TABLE, $date);
        } catch (\Throwable $e) {
            # todo
            return;
        }

        foreach ($ratesRaw['rates'] as $rawData) {
            if ($currency = $this->currencyRepository->findByISO($rawData['code'], $date)) {
                $currency->update($this->currencyRepository, $rawData['currency'], $rawData['mid']);

                continue;
            }

            $rawData['effectiveDate'] = $ratesRaw['effectiveDate'];
            Currency::fromRawData($this->currencyRepository, $rawData);
        }

        $this->currencyRepository->flush();
    }
}
