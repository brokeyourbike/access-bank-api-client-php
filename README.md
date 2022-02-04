# access-bank-api-client

[![Latest Stable Version](https://img.shields.io/github/v/release/brokeyourbike/access-bank-api-client-php)](https://github.com/brokeyourbike/access-bank-api-client-php/releases)
[![Total Downloads](https://poser.pugx.org/brokeyourbike/access-bank-api-client/downloads)](https://packagist.org/packages/brokeyourbike/access-bank-api-client)
[![License: MPL-2.0](https://img.shields.io/badge/license-MPL--2.0-purple.svg)](https://github.com/brokeyourbike/access-bank-api-client-php/blob/main/LICENSE)
[![tests](https://github.com/brokeyourbike/access-bank-api-client-php/actions/workflows/tests.yml/badge.svg)](https://github.com/brokeyourbike/access-bank-api-client-php/actions/workflows/tests.yml)
[![Maintainability](https://api.codeclimate.com/v1/badges/67384c57dff9b7f47aa2/maintainability)](https://codeclimate.com/github/brokeyourbike/access-bank-api-client-php/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/67384c57dff9b7f47aa2/test_coverage)](https://codeclimate.com/github/brokeyourbike/access-bank-api-client-php/test_coverage)

Access Bank API Client for PHP

## Installation

```bash
composer require brokeyourbike/access-bank-api-client
```

## Usage

```php
use BrokeYourBike\AccessBank\Client;
use BrokeYourBike\AccessBank\Interfaces\ConfigInterface;

assert($config instanceof ConfigInterface);
assert($httpClient instanceof \GuzzleHttp\ClientInterface);
assert($psrCache instanceof \Psr\SimpleCache\CacheInterface);

$apiClient = new Client($config, $httpClient, $psrCache);
$apiClient->fetchAuthTokenRaw();
```

## License
[Mozilla Public License v2.0](https://github.com/brokeyourbike/access-bank-api-client-php/blob/main/LICENSE)
