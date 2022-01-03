<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\AccessBank;

use Psr\SimpleCache\CacheInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\ClientInterface;
use BrokeYourBike\ResolveUri\ResolveUriTrait;
use BrokeYourBike\HttpEnums\HttpMethodEnum;
use BrokeYourBike\HttpClient\HttpClientTrait;
use BrokeYourBike\HttpClient\HttpClientInterface;
use BrokeYourBike\HasSourceModel\SourceModelInterface;
use BrokeYourBike\HasSourceModel\HasSourceModelTrait;
use BrokeYourBike\AccessBank\Models\TransactionResponse;
use BrokeYourBike\AccessBank\Models\FetchBankAccountNameResponse;
use BrokeYourBike\AccessBank\Models\FetchAuthTokenResponse;
use BrokeYourBike\AccessBank\Models\FetchAccountBalanceResponse;
use BrokeYourBike\AccessBank\Interfaces\ConfigInterface;
use BrokeYourBike\AccessBank\Interfaces\BankTransactionInterface;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class Client implements HttpClientInterface
{
    use HttpClientTrait;
    use ResolveUriTrait;
    use HasSourceModelTrait;

    private ConfigInterface $config;
    private CacheInterface $cache;
    private int $ttlMarginInSeconds = 60;

    public function __construct(ConfigInterface $config, ClientInterface $httpClient, CacheInterface $cache)
    {
        $this->config = $config;
        $this->httpClient = $httpClient;
        $this->cache = $cache;
    }

    public function getConfig(): ConfigInterface
    {
        return $this->config;
    }

    public function getCache(): CacheInterface
    {
        return $this->cache;
    }

    public function authTokenCacheKey(): string
    {
        return get_class($this) . ':authToken:';
    }

    public function getAuthToken(): string
    {
        if ($this->cache->has($this->authTokenCacheKey())) {
            $cachedToken = $this->cache->get($this->authTokenCacheKey());
            if (is_string($cachedToken)) {
                return $cachedToken;
            }
        }

        $response = $this->fetchAuthTokenRaw();

        $this->cache->set(
            $this->authTokenCacheKey(),
            $response->accessToken,
            (int) $response->expiresIn - $this->ttlMarginInSeconds
        );

        return $response->accessToken;
    }

    public function fetchAuthTokenRaw(): FetchAuthTokenResponse
    {
        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
            ],
            \GuzzleHttp\RequestOptions::FORM_PARAMS => [
                'grant_type' => 'client_credentials',
                'resource' => $this->config->getResourceId(),
                'client_id' => $this->config->getClientId(),
                'client_secret' => $this->config->getClientSecret(),
            ],
        ];

        $response = $this->httpClient->request(
            HttpMethodEnum::POST->value,
            $this->config->getAuthUrl(),
            $options
        );

        return new FetchAuthTokenResponse($response);
    }

    public function fetchAccountBalanceRaw(string $auditId, string $accountNumber): FetchAccountBalanceResponse
    {
        $response = $this->performRequest(HttpMethodEnum::POST, 'getAccountBalance', [
            'accountNumber' => $accountNumber,
            'auditId' => $auditId,
            'appId' => $this->config->getAppId(),
        ]);

        return new FetchAccountBalanceResponse($response);
    }

    public function fetchDomesticBankAccountNameRaw(string $auditId, string $accountNumber): FetchBankAccountNameResponse
    {
        $response = $this->performRequest(HttpMethodEnum::POST, 'getBankAccountName', [
            'accountNumber' => $accountNumber,
            'auditId' => $auditId,
            'appId' => $this->config->getAppId(),
        ]);

        return new FetchBankAccountNameResponse($response);
    }

    public function fetchDomesticTransactionStatusRaw(string $auditId, string $reference): TransactionResponse
    {
        $response = $this->performRequest(HttpMethodEnum::POST, 'getBankFTStatus', [
            'paymentAuditId' => $reference,
            'auditId' => $auditId,
            'appId' => $this->config->getAppId(),
        ]);

        return new TransactionResponse($response);
    }

    public function sendDomesticTransaction(BankTransactionInterface $bankTransaction): TransactionResponse
    {
        if ($bankTransaction instanceof SourceModelInterface) {
            $this->setSourceModel($bankTransaction);
        }

        $response = $this->performRequest(HttpMethodEnum::POST, 'bankAccountFT', [
            'debitAccount' => $bankTransaction->getDebitAccount(),
            'beneficiaryAccount' => $bankTransaction->getRecipientAccount(),
            'beneficiaryName' => $bankTransaction->getRecipientName(),
            'amount' => $bankTransaction->getAmount(),
            'currency' => $bankTransaction->getCurrencyCode(),
            'narration' => $bankTransaction->getDescription(),
            'auditId' => $bankTransaction->getReference(),
            'appId' => $this->config->getAppId(),
        ]);

        return new TransactionResponse($response);
    }

    public function sendOtherBankTransaction(BankTransactionInterface $bankTransaction): ResponseInterface
    {
        if ($bankTransaction instanceof SourceModelInterface) {
            $this->setSourceModel($bankTransaction);
        }

        return $this->performRequest(HttpMethodEnum::POST, 'USDOtherBankFT', [
            'AuditId' => $bankTransaction->getReference(),
            'AppId' => $this->config->getAppId(),
            'DebitAccountNumber' => $bankTransaction->getDebitAccount(),
            'BeneficiaryAccount' => $bankTransaction->getRecipientAccount(),
            'BeneficiaryName' => $bankTransaction->getRecipientName(),
            'Amount' => $bankTransaction->getAmount(),
            'Bank' => $bankTransaction->getBankCode(),
            'Narration' => $bankTransaction->getDescription(),
        ]);
    }

    /**
     * @param HttpMethodEnum $method
     * @param string $uri
     * @param array<mixed> $data
     * @return ResponseInterface
     */
    private function performRequest(HttpMethodEnum $method, string $uri, array $data): ResponseInterface
    {
        $options = [
            \GuzzleHttp\RequestOptions::HEADERS => [
                'Accept' => 'application/json',
                'Authorization' => "Bearer {$this->getAuthToken()}",
                'Ocp-Apim-Subscription-Key' => $this->config->getSubscriptionKey(),
            ],
            \GuzzleHttp\RequestOptions::JSON => $data,
        ];

        if ($this->getSourceModel()) {
            $options[\BrokeYourBike\HasSourceModel\Enums\RequestOptions::SOURCE_MODEL] = $this->getSourceModel();
        }

        $uri = (string) $this->resolveUriFor($this->config->getUrl(), $uri);
        return $this->httpClient->request($method->value, $uri, $options);
    }
}
