<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Order;

class CreateResponse
{
    public string $transactionId;
    public string $redirectUrl;

    public function __construct(string $transactionId, string $redirectUrl)
    {
        $this->transactionId = $transactionId;
        $this->redirectUrl = $redirectUrl;
    }
}
