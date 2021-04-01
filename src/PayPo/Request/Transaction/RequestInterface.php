<?php

declare(strict_types=1);

namespace Answear\PayPo\Request\Transaction;

interface RequestInterface
{
    public function getHttpMethod(): string;

    public function getUrl(): string;
}
