# Smart Payment Routing

Smart Payment Routing is a Laravel package for smartly routing transactions to payment processors(gateways) based on a defined criterial

## Installation

Use the package manager [composer](https://packagist.org/en/stable/) to install Smart Payment Routing

```bash
composer require fearless-ajs/smart-payment-routing
```

## Usage
1. Enter the payment processor's env variables in your .env file or you can also publish smart-payment-config.php.

```env

  PAYSTACK_SECRET=*************
  PAYSTACK_REDIRECT_URL=http://example.com/redirect

  FLUTTERWAVE_SECRET=*************
  FLUTTERWAVE_REDIRECT_URL=http://example.com/redirect

```

2. Inject ProcessorManager class into the class that's going to handle payment transactions, and configure the processor manager by initializing the desired payment processors as shown below.
```php
namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Http\Services\StripeProcessor;
use Fearless\SmartPaymentRouting\core\adapter\ProcessorManager;
use Fearless\SmartPaymentRouting\core\PaymentRoutingService;
use Fearless\SmartPaymentRouting\core\processors\FlutterwaveProcessor;
use Fearless\SmartPaymentRouting\core\processors\PaystackProcessor;
use Illuminate\Http\Request;


class TransactionController extends Controller
{
   private readonly ProcessorManager $processorManager;

    public function __construct(ProcessorManager $processorManager)
    {
        $this->processorManager = $processorManager;
        // Register the processor for transactions within this class
        $processorManager->initialize([
            [
                'name' => 'paystack',
                'processor' => new PaystackProcessor(),
            ],
            [
                'name'  => 'flutterwave',
                'processor' => new FlutterwaveProcessor(),
            ]
        ]);
    }

}
```


2. Then instantiate PaymentRoutingService and pass the ProcessorManager as a parameter.
3. Then call makeCharge method on paymentRoutingService with your transaction data(user_email, amount, and currency) to perform the payment transaction(The default payment processors will only return a payment URL, users will need to be redirected to the payment URL to make the payment)


```php
namespace App\Http\Controllers;

use App\Http\Requests\CreatePaymentRequest;
use App\Http\Services\StripeProcessor;
use Fearless\SmartPaymentRouting\core\adapter\ProcessorManager;
use Fearless\SmartPaymentRouting\core\PaymentRoutingService;
use Fearless\SmartPaymentRouting\core\processors\FlutterwaveProcessor;
use Fearless\SmartPaymentRouting\core\processors\PaystackProcessor;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
        private readonly ProcessorManager $processorManager;

    public function __construct(ProcessorManager $processorManager)
    {
        $this->processorManager = $processorManager;
        // Register the processor for transactions within this class
        $processorManager->initialize([
            [
                'name' => 'paystack',
                'processor' => new PaystackProcessor(),
            ],
            [
                'name'  => 'flutterwave',
                'processor' => new FlutterwaveProcessor(),
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePaymentRequest $request)
    {

        // Initialize the payment routing service
        $paymentRoutingService = new PaymentRoutingService($this->processorManager);
        $transaction = [
            'user_email' => $request->get('email'),
            'amount' => $request->get('amount'),
            'currency' => $request->get('currency'),
        ];

        // Route transaction to the best processor
        return $paymentRoutingService->makeCharge($this->processorManager, $transaction);
    }

}
```
4. To add any other payment processor, simply publish the config file by running
```bash
  php artisan vendor:publish --tag smart-payment-routing-config 
```
Then create a new payment processor class(e.g StripeProcessor) and implement PaymentProcessor interface as shown below
```php
<?php

namespace App\Http\Services;

use Fearless\SmartPaymentRouting\core\contract\PaymentProcessor;
use GuzzleHttp\Exception\GuzzleException;
use MusahMusah\LaravelMultipaymentGateways\Exceptions\HttpMethodFoundException;
use MusahMusah\LaravelMultipaymentGateways\Exceptions\InvalidConfigurationException;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class StripeProcessor implements PaymentProcessor
{
    public function createPayment($user_email, $amount, $currency)
    {
        $priceId = '***************';

        try {

            $stripe = new \Stripe\StripeClient(getenv('STRIPE_SECRET'));
           return $stripe->checkout->sessions->create([
                'success_url' => 'https://example.com/success',
                'line_items' => [
                    [
                        'price' => $priceId,
                        'quantity' => 2,
                    ],
                ],
                'mode' => 'payment',
            ]);
        } catch (GuzzleException|HttpMethodFoundException|InvalidConfigurationException $e) {
            throw new NotAcceptableHttpException($e->getMessage());
        }
    }
}

```
Make sure you add the required env variables to your .env file as shown below
```bash
 
 STRIPE_SECRET=**********
 STRIPE_WEBHOOK_SECRET=**********

```
And lastely update the processorManager as shown below to register the new payment processor
```php
class TransactionController extends Controller
{
   private readonly ProcessorManager $processorManager;

    public function __construct(ProcessorManager $processorManager)
    {
        $this->processorManager = $processorManager;
        // Register the processor for transactions within this class
        $processorManager->initialize([
            [
                'name' => 'paystack',
                'processor' => new PaystackProcessor(),
            ],
            [
                'name'  => 'flutterwave',
                'processor' => new FlutterwaveProcessor(),
            ],
            [
                'name'  => 'stripe',
                'processor' => new StripeProcessor(),
            ]
        ]);
    }

}
```
## Contributing

Pull requests are welcome. For major changes, please open an issue first
to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License

[MIT](https://choosealicense.com/licenses/mit/)
