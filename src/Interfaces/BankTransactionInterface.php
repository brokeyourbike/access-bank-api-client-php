<?php

// Copyright (C) 2021 Ivan Stasiuk <ivan@stasi.uk>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\AccessBank\Interfaces;

/**
 * @author Ivan Stasiuk <ivan@stasi.uk>
 */
interface BankTransactionInterface
{
    public function getReference(): string;
    public function getAmount(): float;
    public function getCurrencyCode(): string;
    public function getBankCode(): string;
    public function getDebitAccount(): string;
    public function getRecipientAccount(): string;
    public function getRecipientName(): string;
    public function getSenderCountry(): string;
    public function getSenderName(): string;
    public function getDescription(): string;
}
