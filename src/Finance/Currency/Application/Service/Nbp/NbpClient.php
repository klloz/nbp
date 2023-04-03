<?php

namespace App\Finance\Currency\Application\Service\Nbp;

use App\Finance\Currency\Application\Exception\InvalidResponseException;
use App\Finance\Currency\Application\Exception\InvalidTableException;
use App\Finance\Currency\Application\Exception\NoExchangeRatesForDateException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class NbpClient
{
    public const BASE_URL = 'http://api.nbp.pl/api';
    public const AVAILABLE_TABLES = ['a', 'b', 'c'];

    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    /**
     * @throws ExceptionInterface
     * @throws NoExchangeRatesForDateException
     * @throws InvalidTableException
     * @throws InvalidResponseException
     * @throws TransportExceptionInterface
     */
    public function getExchangeRates(string $table, \DateTimeInterface $date): array
    {
        if (!in_array($table, self::AVAILABLE_TABLES)) {
            throw new InvalidTableException();
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
            if ($e->getCode() === 404) {
                throw new NoExchangeRatesForDateException();
            }

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
        $expectedKeys = ['currency', 'code', 'mid'];
        $data = $data[0]['rates'] ?? null;

        if (!$data) {
            throw new InvalidResponseException();
        }

        foreach ($data as $item) {
            if (!empty(array_diff(array_keys($item), $expectedKeys))) {
                throw new InvalidResponseException();
            }
        }
    }
}
