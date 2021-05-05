<?php

declare(strict_types=1);

namespace Answear\PayPo\Response\Order;

class Response
{
    public string $code;
    public string $message;

    public function __construct(string $code, string $message)
    {
        $this->code = $code;
        $this->message = $message;
    }
}
