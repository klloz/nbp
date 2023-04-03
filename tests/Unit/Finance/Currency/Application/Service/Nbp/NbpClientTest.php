<?php

namespace Unit\Finance\Currency\Application\Service\Nbp;

use App\Finance\Currency\Application\Service\Nbp\NbpClient;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

final class NbpClientTest extends TestCase
{
    public function testGetExchangeRatesOk(): void
    {
        $table = 'a';
        $date = '2023-04-03';
        $url = sprintf('%s/exchangerates/tables/%s/%s', NbpClient::BASE_URL, $table, $date);
        $expectedResponseData = [
            [
                'table' => 'A',
                'no' => '064/A/NBP/2023',
                'effectiveDate' => $date,
                'rates' => [
                    [
                        'currency' => 'ringgit (Malezja)',
                        'code' => 'MYR',
                        'mid' => '0.9720'
                    ],
                ],
            ],
        ];

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $responseMock = $this->createMock(ResponseInterface::class);

        $httpClientMock->expects($this->once())
            ->method('request')
            ->with('GET', $url)
            ->willReturn($responseMock);

        $responseMock->expects($this->once())
            ->method('getContent')
            ->willReturn(json_encode($expectedResponseData));

        $response = (new NbpClient($httpClientMock))->getExchangeRates(
            $table, \DateTimeImmutable::createFromFormat('Y-m-d', $date)
        );

        $this->assertSame($expectedResponseData[0], $response);
    }
}
