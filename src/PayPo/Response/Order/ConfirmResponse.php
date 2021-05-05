<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Order;

class ConfirmResponse
{
    public string $code;
    public string $statusDescription;

    public function __construct(string $code, string $statusDescription)
    {
        $this->code = $code;
        $this->statusDescription = $statusDescription;
    }
}
