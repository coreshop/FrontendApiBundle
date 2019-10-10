<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2019 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Bundle\ApiBundle\ViewFactory\Product;

use CoreShop\Bundle\ApiBundle\ViewFactory\PriceViewFactoryInterface;
use CoreShop\Bundle\ApiBundle\ViewModel\ProductView;
use CoreShop\Component\Core\Context\ShopperContextInterface;
use CoreShop\Component\Core\Product\TaxedProductPriceCalculatorInterface;
use CoreShop\Component\Product\Model\ProductInterface;
use CoreShop\Component\Store\Model\StoreInterface;
use CoreShop\Component\Order\Model\PurchasableInterface;

final class ProductViewFactory implements ProductViewFactoryInterface
{
    /**
     * @var string
     */
    private $productViewClass;

    /**
     * @var PriceViewFactoryInterface
     */
    private $priceViewFactory;

    /**
     * @var ShopperContextInterface
     */
    private $shopperContext;

    /**
     * @var TaxedProductPriceCalculatorInterface
     */
    private $priceCalculator;

    /**
     * @param string                               $productViewClass
     * @param PriceViewFactoryInterface            $priceViewFactory
     * @param ShopperContextInterface              $shopperContext
     * @param TaxedProductPriceCalculatorInterface $priceCalculator
     */
    public function __construct(
        string $productViewClass,
        PriceViewFactoryInterface $priceViewFactory,
        ShopperContextInterface $shopperContext,
        TaxedProductPriceCalculatorInterface $priceCalculator
    ) {
        $this->productViewClass = $productViewClass;
        $this->priceViewFactory = $priceViewFactory;
        $this->shopperContext = $shopperContext;
        $this->priceCalculator = $priceCalculator;
    }

    /**
     * {@inheritdoc}
     */
    public function create(PurchasableInterface $product, StoreInterface $store, string $locale): ProductView
    {
        /**
         * @var ProductView $productView
         */
        $productView = new $this->productViewClass();
        $productView->id = $product->getId();
        $productView->name = $product->getName($locale);

        if ($product instanceof ProductInterface) {
            $productView->description = $product->getDescription($locale);
            $productView->shortDescription = $product->getShortDescription($locale);
        }

        $productView->retailPrice = $this->priceViewFactory->create(
            $this->priceCalculator->getRetailPrice($product, $this->shopperContext->getContext()),
            $store->getCurrency()->getIsoCode()
        );
        $productView->price = $this->priceViewFactory->create(
            $this->priceCalculator->getPrice($product, $this->shopperContext->getContext()),
            $store->getCurrency()->getIsoCode()
        );
        $productView->discount = $this->priceViewFactory->create(
            $this->priceCalculator->getDiscount($product, $this->shopperContext->getContext()),
            $store->getCurrency()->getIsoCode()
        );
        $productView->discountPrice = $this->priceViewFactory->create(
            $this->priceCalculator->getDiscountPrice($product, $this->shopperContext->getContext()),
            $store->getCurrency()->getIsoCode()
        );

        return $productView;
    }
}
