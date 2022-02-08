<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class AController extends AbstractController
{
    private ?SerializerInterface $serializer = null;

    public function __construct(?SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        if ($this->serializer) {
            $jsonData = $this->serializer->serialize($data, 'json', SerializationContext::create()->setGroups($context['groups']));
            return new JsonResponse(null, $status, $headers, $jsonData);
        } else {
            return parent::json($data, $status, $headers, $context);
        }

    }
}