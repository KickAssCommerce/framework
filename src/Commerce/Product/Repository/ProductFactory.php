<?php

namespace KickAss\Commerce\Product\Repository;
use Symfony\Component\Yaml\Yaml;

class ProductFactory
{
    /**
     * @var \KickAss\Commerce\Application\ProductInterface
     */
    protected $router;

    /**
     * @var \KickAss\Commerce\Application\ProductInterface
     */
    protected $defaultDataProvider;

    /**
     * @var array
     */
    protected $attributeDataProvider;

    /**
     *
     */
    public function __invoke() {
        $registry = Yaml::parse(file_get_contents(APP_BASE_DIR . 'bootstrap/registry.yml'));
        $this->router = new $registry['product']['router']();
        $this->defaultDataProvider = new $registry['product']['data']['default']();

        unset($registry['product']['data']['default']);

        $this->attributeDataProvider = $registry['product']['data'];
    }

    /**
     * @param $key
     * @return \KickAss\Commerce\Application\ProductInterface
     */
    public function getDataProvider($key)
    {
        return $this->attributeDataProvider[$key] ? new $this->attributeDataProvider[$key]() : $this->defaultDataProvider;
    }

    /**
     * @return \KickAss\Commerce\Application\ProductInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return \KickAss\Commerce\Application\ProductInterface
     */
    public function getDefaultDataProvider()
    {
        return $this->defaultDataProvider;
    }

    /**
     * @return array
     */
    public function getAttributeKeys()
    {
        return array_keys($this->attributeDataProvider);
    }
}
