<?php

namespace App\Finance\Currency\Application\Service\Nbp;

use App\Finance\Currency\Application\Exception\InvalidResponseException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class NbpClient
{
    public const BASE_URL = 'http://api.nbp.pl/api';
    public const AVAILABLE_TABLES = ['a', 'b', 'c'];

    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function getExchangeRates(string $table, \DateTimeInterface $date): array
    {
        if (!in_array($table, self::AVAILABLE_TABLES)) {
            # todo throw dedicated exception
            throw new \Exception();
        }

        $url = sprintf(
            '%s/exchangerates/tables/%s/%s',
            self::BASE_URL,
            $table,
            $date->format('Y-m-d')
        );

        try {
            $response = $this->httpClient->request('GET', $url);
            $data = json_decode($response->getContent(), true);
        } catch (ExceptionInterface $e) {
            # todo throw dedicated exception when no data

            throw $e;
        }

        $this->validateExchangeRatesResponse($data);

        return $data[0];
    }

    /**
     * @throws InvalidResponseException
     */
    private function validateExchangeRatesResponse(array $data): void
    {
        $data = $data[0]['rates'] ?? null;

        if (!$data) {
            # todo throw dedicated exception
            throw new \Exception();
        }

        # todo check nested arrays
    }
}
