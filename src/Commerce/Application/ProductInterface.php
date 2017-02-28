<?php

namespace KickAss\Commerce\Application;

interface ProductInterface
{

    /**
     * @param mixed $identifier
     * @return array
     */
    public function getProductItem($identifier);
}
