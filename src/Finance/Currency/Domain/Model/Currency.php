<?php

namespace App\Finance\Currency\Domain\Model;

use App\Common\Finance\Currency\Domain\Model\CurrencyContract;
use App\Finance\Currency\Domain\Repository\CurrencyRepositoryContract;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity(repositoryClass: CurrencyRepositoryContract::class)]
#[ORM\UniqueConstraint(name: 'unique_currency_per_date', columns: ['currency_code', 'date'])]
class Currency implements CurrencyContract
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    private Uuid $id;

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $name;

    # ISO 4217
    #[ORM\Column(type: Types::STRING, length: 3)]
    private readonly string $currencyCode;

    #[ORM\Column(type: Types::INTEGER)]
    private int $exchangeRate;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private readonly \DateTimeInterface $date;

    /**
     * @param Uuid $id
     * @param string $name
     * @param string $currencyCode
     * @param int $exchangeRate
     * @param \DateTimeInterface $date
     */
    private function __construct(
        Uuid $id,
        string $name,
        string $currencyCode,
        int $exchangeRate,
        \DateTimeInterface $date
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->currencyCode = $currencyCode;
        $this->exchangeRate = $exchangeRate;
        $this->date = $date;
    }

    public static function fromRawData(CurrencyRepositoryContract $currencyRepository, mixed $rawData): self
    {
        # todo validation

        $model = new self(
            UuidV4::v4(),
            $rawData['currency'],
            $rawData['code'],
            floor((float)$rawData['mid'] * 10000),
            \DateTimeImmutable::createFromFormat('Y-m-d', $rawData['effectiveDate']),
        );

        $currencyRepository->store($model);

        return $model;
    }

    public function update(
        CurrencyRepositoryContract $currencyRepository,
        string $name,
        float $exchangeRate
    ): self {
        # todo validation

        $this->name = $name;
        $this->exchangeRate = floor($exchangeRate * 10000);

        $currencyRepository->store($this);

        return $this;
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function currencyCode(): string
    {
        return $this->currencyCode;
    }

    public function exchangeRate(): int
    {
        return $this->exchangeRate;
    }

    public function exchangeRateFloat(): float
    {
        return round($this->exchangeRate/10000);
    }

    public function date(): \DateTimeInterface
    {
        return $this->date;
    }
}
