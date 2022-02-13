<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class AController extends AbstractController
{
    private ?SerializerInterface $serializer;

    public function __construct(?SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        if ($this->serializer && $data) {
            $serializationContext = null;
            if (isset($context['groups'])) {
                $serializationContext = SerializationContext::create()->setGroups($context['groups']);
            }
            return new JsonResponse(
                $this->serializer->serialize($data, 'json', $serializationContext),
                $status,
                $headers,
                true
            );
        } else {
            //dd($this->serializer);
            //dd($context);
            return parent::json($data, $status, $headers, $context);
        }

    }
}