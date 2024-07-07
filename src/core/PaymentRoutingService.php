<?php
namespace Fearless\SmartPaymentRouting\core;

use Fearless\SmartPaymentRouting\core\adapter\ProcessorManager;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class PaymentRoutingService
{
    protected ProcessorManager $processorManager;

    public function __construct(ProcessorManager $processorManager)
    {
        $this->processorManager = $processorManager;
    }

    public function route($transaction): mixed
    {
        // check if the registered processors supports the supplied currency
        if (!$this->checkTransactionCurrency($transaction['currency']))
            throw new NotAcceptableHttpException('Invalid currency, please check the currency, and the config');

        try {
            $processors = $this->processorManager->getAllProcessors();
            $routingRules = Config::get('smart-payment-routing.routing_rules');
            $prioritize = $routingRules['prioritize'];
            $thresholds = $routingRules['thresholds'];

            $selectedProcessor = null;
            //  loop through available processors in the config file
            foreach ($processors as $name => $processor) {
                // check if the processor supports the transaction currency
                if ($processor['currencies'] && array_key_exists(strtoupper($transaction['currency']), $processor['currencies'])) {
                    if ($processor[$prioritize] <= $thresholds[$prioritize]) {
                        /*
                         * The $prioritize variable holds the criterion to prioritize (either 'cost' or 'reliability'),
                         *  as defined in the configuration. This line checks if the current processor's value for the
                         *  prioritized criterion is within the acceptable threshold.
                         */
                        if (is_null($selectedProcessor) || $processor[$prioritize] < $selectedProcessor[$prioritize]) {
                            /*
                             * If no processor has been selected yet (is_null($selectedProcessor)) or if the current processor's
                             *  value for the prioritized criterion is better (lower for cost or higher for reliability) than the
                             *  currently selected processor, the current processor becomes the new selected processor.
                             */
                            $selectedProcessor = $processor;
                            $selectedProcessor['name'] = $name;
                        }
                    }
                    // else go for the default processor
                    if (is_null($selectedProcessor)) {
                        $selectedProcessor = $this->getDefaultProcessor($transaction['currency']);
                    }
                }
            }

            return $selectedProcessor;
        } catch (\Exception $e) {
            // Handle the exception
            Log::error('Payment routing failed', ['error' => $e->getMessage()]);
            throw new NotAcceptableHttpException($e->getMessage());
        }
    }

    public function makeCharge(ProcessorManager $processorManager, array $transaction): mixed
    {
        // fetch available processors
        $processors = $this->processorManager->getAllProcessors();
        if (!$processors || count($processors) === 0)
            throw new NotAcceptableHttpException('No processors found, please register a processor first');

        $selectedProcessor = $this->route($transaction);
        // Charge with the selected processor
        $processorName = $selectedProcessor['name'];
        $processor = $processorManager->getProcessor($processorName);
        return $processor['name']->createPayment($transaction['user_email'], $transaction['amount'], $transaction['currency']);
    }

    public function checkTransactionCurrency(string $transactionCurrency): bool
    {
        $processors = $this->processorManager->getAllProcessors();
        foreach ($processors as $name => $processor) {
            foreach ($processor['currencies'] as $currencyName => $currency) {
                if (strtoupper($currencyName) === strtoupper($transactionCurrency)) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getDefaultProcessor(string $transactionCurrency)
    {
        $processors = $this->processorManager->getAllProcessors();

        $default = $processors[Config::get('smart-payment-routing.default')];

        // check if the default payment processor supports the currency
        if (!array_key_exists(strtoupper($transactionCurrency), $default['currencies']))
            throw new NotAcceptableHttpException('Invalid currency, default processor does not support the currency');

        // Override the name and return the processor
        $default['name'] = Config::get('smart-payment-routing.default');
        return $default;
    }
}