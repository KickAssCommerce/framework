<?php

namespace KickAss\Commerce\Product\Repository;

use KickAss\Commerce\Application\ProductInterface as ApplicationProductInterface;
use KickAss\Commerce\Product\Map\Product as MapProduct;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Product implements ProductInterface
{
    /**
     * @var ApplicationProductInterface
     */
    private $router;
    /**
     * @var ApplicationProductInterface
     */
    private $defaultDataprovider;
    /**
     * @var array
     */
    private $dataproviders;

    /**
     * @var ObjectNormalizer
     */
    private $normalizer;

    /**
     * Product constructor.
     *
     * @param ApplicationProductInterface $router
     * @param ApplicationProductInterface $defaultDataprovider
     * @param array $dataproviders
     */
    public function __construct(
        ApplicationProductInterface $router,
        ApplicationProductInterface $defaultDataprovider,
        array $dataproviders
    ) {
        $this->router = $router;
        $this->defaultDataprovider = $defaultDataprovider;
        $this->dataproviders = $dataproviders;
        $this->normalizer = new ObjectNormalizer();
    }

    /**
     * @param int $id
     * @return MapProduct
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    public function load($id)
    {
        $productInfo = $this->defaultDataprovider->getProductItem($id);

        return $this->populateProductRepository($productInfo);
    }

    /**
     * @param string $attribute
     * @param string $value
     * @return MapProduct
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    public function loadByAttribute(string $attribute, string $value)
    {
        $productInfo = $this->defaultDataprovider->getProductItemByAttribute($attribute, $value);

        return $this->populateProductRepository($productInfo);
    }

    /**
     * @param array $productData
     * @return MapProduct
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    private function populateProductRepository(array $productData)
    {
        /** @var MapProduct $product */
        $product = $this->normalizer->denormalize($productData, MapProduct::class);
        return $product;
    }

    /**
     * @param array $filters
     * @return MapProduct[]
     * @throws \Symfony\Component\Serializer\Exception\UnexpectedValueException
     */
    public function search(array $filters = array())
    {
        $productInfo = $this->defaultDataprovider->getProductList($filters);
        $products = [];
        foreach ($productInfo as $product) {
            $products[] = $this->normalizer->denormalize($product, MapProduct::class);
        }
        return $products;
    }
}
