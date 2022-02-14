<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AController extends AbstractController
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $serializationContext = SerializationContext::create()->setSerializeNull(true);
        if(isset($context['groups'])) {
            $serializationContext->setGroups($context['groups']);
        }
        return new JsonResponse(
            $this->serializer->serialize($data, 'json', $serializationContext),
            $status,
            $headers,
            true
        );
    }
}