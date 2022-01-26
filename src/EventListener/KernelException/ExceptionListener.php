<?php


namespace App\EventListener\KernelException;

use App\Normalizer\INormalizer;
use Exception;
use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{

    /**
     * @var INormalizer[]
     */
    private array $normalizers;

    public function __construct(RewindableGenerator $normalizers)
    {
        $this->normalizers = iterator_to_array($normalizers);
    }

    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        if (!$exception instanceof Exception) {
            return;
        }

        $response = null;
        foreach ($this->normalizers as $normalizer) {
            if ($normalizer->support($exception)) {
                $response = $normalizer->normalize($exception);
            }
        }

        if (!$response) {
            $response = new JsonResponse(
                ['message' => 'Internal Server Error'],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        $event->setResponse($response);
    }
}