<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\AccessBank\Enums;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 *
 * @method static ErrorCodeEnum NO_ERROR()
 * @method static ErrorCodeEnum UNAUTHORIZED()
 * @method static ErrorCodeEnum DUPLICATE_REQUEST()
 * @method static ErrorCodeEnum NO_RECORD()
 * @method static ErrorCodeEnum INVALID_DEBIT_ACCOUNT()
 * @method static ErrorCodeEnum RECONFIRM_BENEFICIARY_ACCOUNT()
 * @method static ErrorCodeEnum UNABLE_TO_PROCESS_REQUEST()
 * @method static ErrorCodeEnum BENEFICIARY_ACCOUNT_NO_PERMITTED()
 * @method static ErrorCodeEnum INSUFFICIENT_FUNDS()
 * @method static ErrorCodeEnum INVALID_ACCOUNT_NUMBER()
 * @method static ErrorCodeEnum UNABLE_TO_PROCESS_ON_NIBSS()
 * @method static ErrorCodeEnum UNABLE_TO_DEBIT()
 * @method static ErrorCodeEnum INVALID_CREDIT_ACCOUNT()
 * @method static ErrorCodeEnum NOT_PERMITTED()
 * @psalm-immutable
 */
final class ErrorCodeEnum extends \MyCLabs\Enum\Enum
{
    /**
     * No error.
     */
    private const NO_ERROR = '0';

    /**
     * Unauthorized access.
     */
    private const UNAUTHORIZED = '10';

    /**
     * Repeated request detected. Change audit Id.
     */
    private const DUPLICATE_REQUEST = '11';

    /**
     * No record found.
     */
    private const NO_RECORD = '12';

    /**
     * Invalid or unregistered debit account.
     */
    private const INVALID_DEBIT_ACCOUNT = '13';

    /**
     * Reconfirm beneficiary account. Retry or contact support.
     */
    private const RECONFIRM_BENEFICIARY_ACCOUNT = '14';

    /**
     * Unable to process request. Retry or contact support.
     */
    private const UNABLE_TO_PROCESS_REQUEST = '15';

    /**
     * Beneficiary account not permitted.
     */
    private const BENEFICIARY_ACCOUNT_NO_PERMITTED = '16';

    /**
     * Source account insufficiently funded.
     */
    private const INSUFFICIENT_FUNDS = '17';

    /**
     * Invalid account number.
     */
    private const INVALID_ACCOUNT_NUMBER = '18';

    /**
     * Unable to process on NIBSS.
     */
    private const UNABLE_TO_PROCESS_ON_NIBSS = '19';

    /**
     * Unable to debit account. Check balance before retry.
     */
    private const UNABLE_TO_DEBIT = '20';

    /**
     * Invalid or unregistered credit account.
     */
    private const INVALID_CREDIT_ACCOUNT = '21';

    /**
     * Declined - Transaction not permitted. Confirm request or contact support.
     */
    private const NOT_PERMITTED = '24';
}