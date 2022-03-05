<?php

namespace App\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

abstract class AController extends AbstractController
{
    private SerializerInterface $serializer;
    private TagAwareCacheInterface $cache;

    public function __construct(SerializerInterface $serializer, TagAwareCacheInterface $cache)
    {
        $this->serializer = $serializer;
        $this->cache = $cache;
    }

    public function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $serializationContext = SerializationContext::create()->setSerializeNull(true);
        if (isset($context['groups'])) {
            $serializationContext->setGroups($context['groups']);
        }
        return new JsonResponse(
            $this->serializer->serialize($data, 'json', $serializationContext),
            $status,
            $headers,
            true
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function cacheHandle($cacheKey, $cacheValue, $cacheTags = [], $cacheDuration = 3600)
    {
        //dd($this->cache->getItem('product.list'));
        return $this->cache->get(
            $cacheKey,
            function (ItemInterface $item) use ($cacheValue, $cacheTags, $cacheDuration) {
                $item->expiresAfter($cacheDuration);
                $item->tag($cacheTags);
                return $cacheValue;
            });
    }
}