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
$orderService->refund('transaction-uuid', 123);
$orderService->getStatusDetails('transaction-uuid');
$orderService->cancel('transaction-uuid');
```

Final notes
------------

Feel free to open pull requests with new features, improvements or bug fixes. The Answear team will be grateful for any comments.

