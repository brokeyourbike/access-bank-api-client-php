<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace App\Enums\AccessBank;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 *
 * @method static StatusCode PENDING()
 * @method static StatusCode SUCCESS()
 * @method static StatusCode PROCESSING()
 * @method static StatusCode FAILED()
 * @method static StatusCode UNKNOWN()
 * @psalm-immutable
 */
final class StatusCode extends \MyCLabs\Enum\Enum
{
    /**
     * Transaction queued for processing.
     */
    private const PENDING = '0';

    /**
     * Transaction completed.
     */
    private const SUCCESS = '1';

    /**
     * Still processing transaction.
     */
    private const PROCESSING = '2';

    /**
     * Transaction failed - no debit or debit reversed.
     */
    private const FAILED = '3';

    /**
     * Queued for manual reconciliation with 24 hours SLA.
     */
    private const UNKNOWN = '4';
}
