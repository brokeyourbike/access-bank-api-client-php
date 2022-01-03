<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\AccessBank\Models;

use Spatie\DataTransferObject\Attributes\MapFrom;
use BrokeYourBike\DataTransferObject\JsonResponse;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
class TransactionResponse extends JsonResponse
{
    public bool $success;
    public string $errorCode;
    public string $message;

    #[MapFrom('payment.transactionId')]
    public ?string $transactionId;

    #[MapFrom('payment.status')]
    public ?string $transactionStatus;

    #[MapFrom('payment.information')]
    public ?string $transactionInformation;
}

