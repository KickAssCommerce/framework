<?php

namespace KickAss\Commerce\Product\Cache;

use Go\Aop\Aspect;
use Go\Aop\Intercept\FieldAccess;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Pointcut;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Cache\Adapter\Filesystem\FilesystemCachePool;
use KickAss\Commerce\Product\Map\Product as MapProduct;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Product aspects
 */
class Product implements Aspect
{

    /**
     * Cache bridge file response
     *
     * @param MethodInvocation $invocation
     * @return \KickAss\Commerce\Product\Map\Product
     *
     * @Around("execution(public KickAss\Commerce\Product\Repository\Product->loadByAttribute(*))")
     */
    public function afterMethodExecution(MethodInvocation $invocation)
    {
        $filesystemAdapter = new Local(APP_BASE_DIR . 'storage/cache/api/');
        $filesystem        = new Filesystem($filesystemAdapter);

        $pool = new FilesystemCachePool($filesystem);

        $cacheKey = "products_" . md5(implode('_', $invocation->getArguments()));

        $item = $pool->getItem($cacheKey);
        $normalizer = new ObjectNormalizer();

        if ($item->isHit()) { // found the item in cache
            /** @var MapProduct $cacheItem */
            $cacheItem = $normalizer->denormalize($item->get(), MapProduct::class);
            return $cacheItem;
        }

        // execute the API call
        /** @var \KickAss\Commerce\Product\Map\Product $returnValue */
        $returnValue = $invocation->proceed();

        $item->set($normalizer->normalize($returnValue, 'array'))
            ->setTags(['products']);
        $item->expiresAfter(3600);

        $pool->save($item);

        // return value as expected
        return $returnValue;
    }
}
