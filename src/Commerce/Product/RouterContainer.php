<?php

namespace KickAss\Commerce\Product;

use KickAss\Commerce\Product\Controller\Listing;
use KickAss\Commerce\Product\Controller\View;
use KickAss\Commerce\Product\Repository\ProductFactory;
use KickAss\Moltin\Bridge\Moltin\Authenticator;
use KickAss\Moltin\Bridge\Moltin\Product as BridgeProduct;

class RouterContainer
{
    public static function view()
    {
        return new View(
            new Authenticator(),
            new ProductFactory()
        );
    }

    public static function listing()
    {
        /*
        return new Listing(
            new Authenticator(),
            new Product(
                new BridgeProduct(),
                new ObjectNormalizer()
            )
        );
        */
    }
}
