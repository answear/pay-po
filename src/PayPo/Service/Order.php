<?php

declare(strict_types=1);

namespace Answear\PayPo\Service;

use Answear\PayPo\Exception\BadResponseException;
use Answear\PayPo\Exception\PrepareRequestException;
use Answear\PayPo\Exception\ServiceUnavailable;
use Answear\PayPo\Request\Transaction\ConfirmRequest;
use Answear\PayPo\Request\Transaction\CreateRequest;
use Answear\PayPo\Request\Transaction\RefundRequest;
use Answear\PayPo\Request\Transaction\RejectRequest;
use Answear\PayPo\Request\Transaction\RequestInterface;
use Answear\PayPo\Request\Transaction\StatusDetailsRequest;
use Answear\PayPo\Response\Order\ConfirmResponse;
use Answear\PayPo\Response\Order\CreateResponse;
use Answear\PayPo\Response\Order\Response;
use Answear\PayPo\Response\Order\StatusResponse;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class Order
{
    private ?PayPoSerializer $serializer;
    private PayPoClient $client;

    public function __construct(?PayPoClient $client = null, ?PayPoSerializer $serializer = null)
    {
        $this->serializer = $serializer;
        $this->client = $client ?? new PayPoClient();
    }

    /**
     * @throws ServiceUnavailable
     * @throws PrepareRequestException
     * @throws BadResponseException
     */
    public function create(CreateRequest $request): CreateResponse
    {
        return $this->handleRequest($request, CreateResponse::class);
    }

    /**
     * @throws ServiceUnavailable
     * @throws PrepareRequestException
     * @throws BadResponseException
     */
    public function confirm(string $transactionUuid): ConfirmResponse
    {
        $request = new ConfirmRequest($transactionUuid);

        return $this->handleRequest($request, ConfirmResponse::class);
    }

    /**
     * @throws ServiceUnavailable
     * @throws PrepareRequestException
     * @throws BadResponseException
     */
    public function reject(string $transactionUuid): ConfirmResponse
    {
        $request = new RejectRequest($transactionUuid);

        return $this->handleRequest($request, ConfirmResponse::class);
    }

    /**
     * @throws ServiceUnavailable
     * @throws PrepareRequestException
     * @throws BadResponseException
     */
    public function getStatusDetails(string $transactionUuid): StatusResponse
    {
        $request = new StatusDetailsRequest($transactionUuid);

        return $this->handleRequest($request, StatusResponse::class);
    }

    /**
     * @throws ServiceUnavailable
     * @throws PrepareRequestException
     * @throws BadResponseException
     */
    public function refund(string $transactionUuid, int $amount): Response
    {
        $request = new RefundRequest($transactionUuid, $amount);

        return $this->handleRequest($request, Response::class);
    }

    /**
     * @return object|mixed
     *
     * @throws ServiceUnavailable
     * @throws PrepareRequestException
     * @throws BadResponseException
     */
    private function handleRequest(RequestInterface $request, string $responseClass)
    {
        $response = $this->sendRequest($request);

        try {
            return $this->getSerializer()->unserialize(
                $response->getBody()->getContents(),
                $responseClass
            );
        } catch (\Throwable $t) {
            throw new BadResponseException($response, $t);
        }
    }

    private function sendRequest(RequestInterface $request): ResponseInterface
    {
        try {
            $body = ('GET' === $request->getHttpMethod()) ? null : $this->getSerializer()->serialize($request);
        } catch (\Throwable $t) {
            throw new PrepareRequestException($request, $t);
        }

        try {
            $response = $this->client->send($request, $body);

            if ($response->getBody()->isSeekable()) {
                $response->getBody()->rewind();
            }
        } catch (GuzzleException $e) {
            throw new ServiceUnavailable($e->getMessage(), $e->getCode(), $e);
        }

        return $response;
    }

    private function getSerializer(): PayPoSerializer
    {
        if (null === $this->serializer) {
            $this->serializer = new PayPoSerializer();
        }

        return $this->serializer;
    }
}
