<?php

namespace KickAss\Moltin\Bridge\Moltin\Product;

use Moltin\SDK\Facade\Product as MoltinProduct;
use KickAss\Commerce\Product\Exception\ProductNotFoundException as ProductException;

class Price implements \KickAss\Commerce\Application\ProductInterface
{

    /**
     * @param mixed $identifier
     * @return array
     */
    public function getProductItem($identifier)
    {
        $product = MoltinProduct::Get($identifier);
        return $product['result'];
    }
}
