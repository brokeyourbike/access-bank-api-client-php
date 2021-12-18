<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\AccessBank\Tests;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use BrokeYourBike\AccessBank\Interfaces\ConfigInterface;
use BrokeYourBike\AccessBank\Client;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 */
class FetchAuthTokenRawTest extends TestCase
{
    /** @test */
    public function it_can_prepare_request(): void
    {
        $mockedConfig = $this->getMockBuilder(ConfigInterface::class)->getMock();
        $mockedConfig->method('getAuthUrl')->willReturn('https://auth.example/');
        $mockedConfig->method('getClientId')->willReturn('client-id');
        $mockedConfig->method('getClientSecret')->willReturn('super-secret-value');
        $mockedConfig->method('getResourceId')->willReturn('resource-id');

        $mockedResponse = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $mockedResponse->method('getStatusCode')->willReturn(200);
        $mockedResponse->method('getBody')
            ->willReturn('{
                "token_type": "Bearer",
                "expires_in": "3599",
                "ext_expires_in": "3599",
                "expires_on": "1625077289",
                "not_before": "1625073389",
                "resource": "12345",
                "access_token": "super-secure-token"
            }');

        /** @var \Mockery\MockInterface $mockedClient */
        $mockedClient = \Mockery::mock(\GuzzleHttp\Client::class);
        $mockedClient->shouldReceive('request')->withArgs([
            'POST',
            'https://auth.example/',
            [
                \GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
                \GuzzleHttp\RequestOptions::HEADERS => [
                    'Accept' => 'application/json',
                ],
                \GuzzleHttp\RequestOptions::FORM_PARAMS => [
                    'grant_type' => 'client_credentials',
                    'resource' => 'resource-id',
                    'client_id' => 'client-id',
                    'client_secret' => 'super-secret-value',
                ],
            ],
        ])->once()->andReturn($mockedResponse);

        $mockedCache = $this->getMockBuilder(CacheInterface::class)->getMock();

        /**
         * @var ConfigInterface $mockedConfig
         * @var \GuzzleHttp\Client $mockedClient
         * @var CacheInterface $mockedCache
         * */
        $api = new Client($mockedConfig, $mockedClient, $mockedCache);
        $requestResult = $api->fetchAuthTokenRaw();

        $this->assertInstanceOf(ResponseInterface::class, $requestResult);
    }
}
