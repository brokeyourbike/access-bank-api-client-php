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
enum ErrorCodeEnum: string
{
    /**
     * No error.
     */
    case NO_ERROR = '0';

    /**
     * Unauthorized access.
     */
    case UNAUTHORIZED = '10';

    /**
     * Repeated request detected. Change audit Id.
     */
    case DUPLICATE_REQUEST = '11';

    /**
     * No record found.
     */
    case NO_RECORD = '12';

    /**
     * Invalid or unregistered debit account.
     */
    case INVALID_DEBIT_ACCOUNT = '13';

    /**
     * Reconfirm beneficiary account. Retry or contact support.
     */
    case RECONFIRM_BENEFICIARY_ACCOUNT = '14';

    /**
     * Unable to process request. Retry or contact support.
     */
    case UNABLE_TO_PROCESS_REQUEST = '15';

    /**
     * Beneficiary account not permitted.
     */
    case BENEFICIARY_ACCOUNT_NO_PERMITTED = '16';

    /**
     * Source account insufficiently funded.
     */
    case INSUFFICIENT_FUNDS = '17';

    /**
     * Invalid account number.
     */
    case INVALID_ACCOUNT_NUMBER = '18';

    /**
     * Unable to process on NIBSS.
     */
    case UNABLE_TO_PROCESS_ON_NIBSS = '19';

    /**
     * Unable to debit account. Check balance before retry.
     */
    case UNABLE_TO_DEBIT = '20';

    /**
     * Invalid or unregistered credit account.
     */
    case INVALID_CREDIT_ACCOUNT = '21';

    /**
     * Declined - Transaction not permitted. Confirm request or contact support.
     */
    case NOT_PERMITTED = '24';
}
