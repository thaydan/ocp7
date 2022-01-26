<?php

namespace App\Normalizer;

use Exception;
use Symfony\Component\HttpFoundation\Response;

interface INormalizer
{
    public function support(Exception $exception): bool;

    public function normalize(Exception $exception): Response;
}