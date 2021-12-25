<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\AccessBank\Enums;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
enum StatusCodeEnum: string
{
    /**
     * Transaction queued for processing.
     */
    case PENDING = '0';

    /**
     * Transaction completed.
     */
    case SUCCESS = '1';

    /**
     * Still processing transaction.
     */
    case PROCESSING = '2';

    /**
     * Transaction failed - no debit or debit reversed.
     */
    case FAILED = '3';

    /**
     * Queued for manual reconciliation with 24 hours SLA.
     */
    case UNKNOWN = '4';
}
