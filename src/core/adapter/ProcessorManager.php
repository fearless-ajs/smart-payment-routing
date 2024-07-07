<?php

namespace Fearless\SmartPaymentRouting\core\adapter;

use Illuminate\Support\Facades\Config;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;

class ProcessorManager
{

    public function __construct()
    {
    }

    protected array $processors = [];

    public function initialize(array $processors): void
    {
        foreach ($processors as $processor) {
         $this->registerProcessor($processor['name'], $processor['processor']);
        }
    }

    public function registerProcessor($name, $processor): void
    {
        $processorsConfig = Config::get('smart-payment-routing.processors');
        $processorConfig = $processorsConfig[$name];
        if (!$processorConfig)
            throw new NotAcceptableHttpException("Please configure the processor: {$name}");

        $selectedProcessor = $processorConfig;
        $selectedProcessor['name'] = $processor;
        $this->processors[$name] = $selectedProcessor;
    }

    public function getProcessor($name)
    {
        return $this->processors[$name] ?? null;
    }

    public function getAllProcessors(): array
    {
        return $this->processors;
    }

    public function removeProcessor($name): void
    {
        unset($this->processors[$name]);
    }
}