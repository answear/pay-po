<?php

declare(strict_types=1);

namespace Answear\PayPo\Exception;

use Answear\PayPo\Request\Transaction\RequestInterface;

class PrepareRequestException extends \RuntimeException
{
    private RequestInterface $request;

    public function __construct(RequestInterface $request, ?\Throwable $t)
    {
        parent::__construct('Prepare request error.', 0, $t);
        $this->request = $request;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
