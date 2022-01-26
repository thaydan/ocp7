<?php

namespace App\Normalizer;

use Exception;

abstract class ANormalizer implements INormalizer
{
    abstract protected function getAcceptedException(): string;

    public function support(Exception $exception): bool
    {
        $acceptedException = $this->getAcceptedException();

        return $exception instanceof $acceptedException;
    }
}