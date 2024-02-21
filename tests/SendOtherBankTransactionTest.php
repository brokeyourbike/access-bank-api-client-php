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
use BrokeYourBike\AccessBank\Interfaces\BankTransactionInterface;
use BrokeYourBike\AccessBank\Client;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class SendOtherBankTransactionTest extends TestCase
{
    private string $appId = 'app-id';
    private string $clientSecret = 'secure-token';
    private string $subscriptionKey = 'subscription-key';

    /** @test */
    public function it_can_prepare_request(): void
    {
        /** @var BankTransactionInterface $transaction */
        $transaction = $this->getMockBuilder(BankTransactionInterface::class)->getMock();

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getAppId')->willReturn($this->appId);
        $mockedConfig->method('getClientSecret')->willReturn($this->clientSecret);
        $mockedConfig->method('getSubscriptionKey')->willReturn($this->subscriptionKey);

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "payment": {
                    "transactionId": "BANKAPI123456789",
                    "status": 1,
                    "information": "Transaction completed successfully"
                },
                "errorCode": 0,
                "message": "Success - Approved or successfully processed",
                "success": true
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://api.example/otherBankAccountFT',
            [
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                    'Authorization' => "Bearer {$this->clientSecret}",
                    'Ocp-Apim-Subscription-Key' => $this->subscriptionKey,
                ],
                \GuzzleHttp\RequestOptions::JSON => [
                    'auditId' => $transaction->getReference(),
                    'appId' => $this->appId,
                    'debitAccountNumber' => $transaction->getDebitAccount(),
                    'beneficiaryAccount' => $transaction->getRecipientAccount(),
                    'beneficiaryName' => $transaction->getRecipientName(),
                    'amount' => $transaction->getAmount(),
                    'bank' => $transaction->getBankCode(),
                    'narration' => $transaction->getDescription(),
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

        $requestResult = $api->sendOtherBankTransaction($transaction);

        $this->assertInstanceOf(TransactionResponse::class, $requestResult);
        $this->assertSame('BANKAPI123456789', $requestResult->transactionId);
        $this->assertSame('0', $requestResult->errorCode);
        $this->assertSame('1', $requestResult->transactionStatus);
        $this->assertSame(true, $requestResult->success);
    }

    /** @test */
    public function it_can_handle_failed_reponse()
    {
        /** @var BankTransactionInterface $transaction */
        $transaction = $this->getMockBuilder(BankTransactionInterface::class)->getMock();

        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getUrl')->willReturn('https://api.example/');
        $mockedConfig->method('getAppId')->willReturn($this->appId);
        $mockedConfig->method('getClientSecret')->willReturn($this->clientSecret);
        $mockedConfig->method('getSubscriptionKey')->willReturn($this->subscriptionKey);

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(500);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "statusCode": 500,
                "message": "Internal server error",
                "activityId": "c1f6bbd9-096f-4c5f-b04f-fbfae795b0ea"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->once()->andReturn($mockedResponse);

        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();
        $mockedCache->method('has')->willReturn(true);
        $mockedCache->method('get')->willReturn($this->clientSecret);

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);

        $requestResult = $api->sendOtherBankTransaction($transaction);

        $this->assertInstanceOf(TransactionResponse::class, $requestResult);
        $this->assertSame('Internal server error', $requestResult->message);
    }
}
