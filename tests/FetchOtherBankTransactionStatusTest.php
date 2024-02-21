<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\AccessBank\Tests;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\AccessBank\Models\TransactionResponse;
use BrokeYourBike\AccessBank\Interfaces\ConfigInterface;
use BrokeYourBike\AccessBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class FetchOtherBankTransactionStatusTest extends TestCase
{
    private string $appId = 'app-id';
    private string $clientSecret = 'secure-token';
    private string $subscriptionKey = 'subscription-key';
    private string $auditId = '12345';

    /** @test */
    public function it_can_prepare_request(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getAppId')->willReturn($this->appId);
        $mockedConfig->method('getClientSecret')->willReturn($this->clientSecret);
        $mockedConfig->method('getSubscriptionKey')->willReturn($this->subscriptionKey);

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "payment": null,
                "errorCode": 12,
                "message": "Failed - No record found",
                "success": false
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/getOtherBankFTStatus',
            [
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$this->clientSecret}",
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'paymentAuditId' => '123456789',
                    'auditId' => $this->auditId,
                    'appId' => $this->appId,
                ],
            ],
        ])->once()->andReturn($mockedResponse);

        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $mockedCache->method('has')->willReturn(true);
        $mockedCache->method('get')->willReturn($this->clientSecret);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        $requestResult = $api->fetchOtheBankTransactionStatus($this->auditId, '123456789');

        $this->assertInstanceOf(TransactionResponse::class, $requestResult);
    }
}
