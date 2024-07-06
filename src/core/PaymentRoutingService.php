<?php
namespace Fearless\SmartPaymentRouting\core;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class PaymentRoutingService
{
    protected mixed $processors;

    public function __construct()
    {
        $this->processors = Config::get('smart-payment-routing.processors');
    }

    public function route($transaction): array|null
    {
        try {
            $routingRules = Config::get('smart-payment-routing.routing_rules');
            $prioritize = $routingRules['prioritize'];
            $thresholds = $routingRules['thresholds'];

            $selectedProcessor = null;
            //  loop through available processors in the config file
            foreach ($this->processors as $name => $processor) {
                // check if the processor supports the transaction currency
                if ($processor['currencies'] && array_key_exists($transaction->currency, $processor['currencies'])){
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
                        $selectedProcessor = $this->processors[Config::get('smart-payment-routing.default')];
                    }
                }
            }

//            if (is_null($selectedProcessor)) {
//                $selectedProcessor = $this->processors[Config::get('smart-payment-routing.default')];
//            }

            return $selectedProcessor;
//            $encryptedDetails = Crypt::encrypt($transaction['details']);
            // Use encrypted details
        } catch (\Exception $e) {
            Log::error('Payment routing failed', ['error' => $e->getMessage()]);
            // Handle the exception
        }
    }
}