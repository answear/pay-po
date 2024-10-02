<?php

declare(strict_types=1);

namespace Answear\PayPo\Exception;

use Answear\PayPo\Request\Transaction\RequestInterface;

class PrepareRequestException extends \RuntimeException
{
    public function __construct(
        public readonly RequestInterface $request,
        ?\Throwable $t,
    ) {
        parent::__construct('Prepare request error.', 0, $t);
    }
}
