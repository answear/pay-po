# PayPo PHP library

Documentation of the API can be found here:
* PL - [https://paypo.pl/biznes/integracja](https://paypo.pl/biznes/integracja).
* RO - [https://paypo.ro/p/comercianti/integrare](https://paypo.ro/p/comercianti/integrare).


Installation
------------

* install with Composer
```
composer require answear/pay-po
```


Usage
------------

```php
use Answear\PayPo\Configuration\PayPoConfiguration;
use Answear\PayPo\Request\Transaction\CreateRequest;
use Answear\PayPo\Service\Order;
//...

PayPoConfiguration::setForSandbox('clientId', 'apiKey', 'api.sandbox.paypo.pl');

$orderService = new Order();
$registerResponse = $orderService->create(new CreateRequest(...));

$redirectUrl = $registerResponse->redirectUrl;
//...

//others requests
$orderService->confirm('transaction-uuid');
$orderService->refund('transaction-uuid', 123, 'our-refund-id');
$orderService->getStatusDetails('transaction-uuid');
$orderService->cancel('transaction-uuid');
```


Refunds
------------

Pass your own refund identifier (`referenceRefundId`, max 68 characters) to every refund request.
PayPo does not return an identifier of its own, so this is the only way to match a refund later on.

```php
$orderService->refund('transaction-uuid', 123, $refundUuid);
```

Refunds registered on a transaction are returned by `getStatusDetails()` as `Refund[]`, but only
when PayPo enabled the extended status response for given merchant. Without it `refunds` is `null`,
which is not the same as an empty list - do not treat a missing refund as a failed one in that case.
Refunds created outside of your system, eg. in the PayPo panel, have no `referenceRefundId`.
A refund entry without `amount` or `created` makes `getStatusDetails()` throw `BadResponseException`.

```php
$status = $orderService->getStatusDetails('transaction-uuid');

if (null === $status->refunds) {
    //extended status response disabled, refunds cannot be verified
} else {
    $status->findRefund($refundUuid);
}
```


Notifications
------------

`settlementStatus` and `message` are sent only with the notification issued when PayPo generates
the transfer specification. A plain status or amount change notification carries neither.

```php
use Answear\PayPo\Response\Notify;

$notify = Notify::fromRawNotify($requestPayload);

if ($notify->isSettlementNotify()) {
    $notify->settlementStatus; //null for a status not known to this library
    $notify->message;
}
```

The signature to verify is sent in the `Notify::SIGNATURE_HEADER` header.

```php
use Answear\PayPo\Util\AuthenticationUtil;

AuthenticationUtil::assertSignatureValid($signature, 'POST', '/notifyUrl', $requestPayload, $merchantApiKey);
```


Error handling
------------

`ApiErrorException` means PayPo received the request and rejected it with a 4xx - eg. a 409 when
cancelling an already completed transaction, or a 400 with the reason in `$error->message` and
every validation error in `$error->errors`. `ServiceUnavailable` means PayPo could not be reached
at all and the request may be worth retrying.

```php
use Answear\PayPo\Exception\ApiErrorException;
use Answear\PayPo\Exception\ServiceUnavailable;

try {
    $orderService->refund('transaction-uuid', 123, $refundUuid);
} catch (ApiErrorException $exception) {
    $exception->statusCode;
    $exception->error?->message;
    $exception->error?->errors;
} catch (ServiceUnavailable $exception) {
    //retry
}
```


Final notes
------------

Feel free to open pull requests with new features, improvements or bug fixes. The Answear team will be grateful for any comments.

