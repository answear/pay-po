# PayPo PHP library

Documentation of the API can be found here: https://paypo.pl/home/integracja.


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

PayPoConfiguration::setForSandbox(6, 'apiKey');

$orderService = new Order();
$registerResponse = $orderService->create(new CreateRequest(...));

$redirectUrl = $registerResponse->redirectUrl;
//...

//others requests
$orderService->confirm('transaction-uuid');
$orderService->refund('transaction-uuid', 123);
$orderService->reject('transaction-uuid');
$orderService->getStatusDetails('transaction-uuid');
```

Final notes
------------

Feel free to open pull requests with new features, improvements or bug fixes. The Answear team will be grateful for any comments.

