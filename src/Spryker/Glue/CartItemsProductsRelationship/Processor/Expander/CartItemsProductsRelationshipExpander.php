<?php

/**
 * Copyright© 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartItemsProductsRelationship\Processor\Expander;

use Spryker\Glue\CartItemsProductsRelationship\Dependency\RestResource\CartItemsProductsRelationToProductsRestApiInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

class CartItemsProductsRelationshipExpander implements CartItemsProductsRelationshipExpanderInterface
{
    protected const SKU_ATTRIBUTE = 'sku';

    /**
     * @var \Spryker\Glue\CartItemsProductsRelationship\Dependency\RestResource\CartItemsProductsRelationToProductsRestApiInterface
     */
    protected $productsResource;

    /**
     * @param \Spryker\Glue\CartItemsProductsRelationship\Dependency\RestResource\CartItemsProductsRelationToProductsRestApiInterface $productsResource
     */
    public function __construct(CartItemsProductsRelationToProductsRestApiInterface $productsResource)
    {
        $this->productsResource = $productsResource;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            if ($resource->getAttributes() === null || !$resource->getAttributes()->offsetExists(static::SKU_ATTRIBUTE)) {
                continue;
            }

            $sku = $resource->getAttributes()->offsetGet(static::SKU_ATTRIBUTE);
            $productResource = $this->productsResource->findProductConcreteBySku($sku, $restRequest);

            if ($productResource !== null) {
                $resource->addRelationship($productResource);
            }
        }
    }
}
