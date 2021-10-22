<?php

// Copyright (C) 2021 Ivan Stasiuk <brokeyourbike@gmail.com>.
//
// This Source Code Form is subject to the terms of the Mozilla Public
// License, v. 2.0. If a copy of the MPL was not distributed with this file,
// You can obtain one at https://mozilla.org/MPL/2.0/.

namespace BrokeYourBike\AccessBank\Interfaces;

/**
 * @author Ivan Stasiuk <brokeyourbike@gmail.com>
 */
interface ConfigInterface
{
    public function isLive(): bool;
    public function getUrl(): string;
    public function getAuthUrl(): string;
    public function getAppId(): string;
    public function getClientId(): string;
    public function getClientSecret(): string;
    public function getResourceId(): string;
    public function getSubscriptionKey(): string;
}
