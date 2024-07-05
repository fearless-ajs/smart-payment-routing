<?php

namespace Fearless\SmartPaymentRouting\core;

class ProcessorManager
{
    protected array $processors = [];

    public function registerProcessor($name, $processor): void
    {
        $this->processors[$name] = $processor;
    }

    public function getProcessor($name)
    {
        return $this->processors[$name] ?? null;
    }
}