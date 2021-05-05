<?php

declare(strict_types=1);

namespace Answear\PayPo\Exception;

use Psr\Http\Message\ResponseInterface;

class BadResponseException extends \RuntimeException
{
    private ResponseInterface $response;

    public function __construct(ResponseInterface $response, ?\Throwable $t)
    {
        parent::__construct('Response error.', 0, $t);
        $this->response = $response;
    }

    public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
