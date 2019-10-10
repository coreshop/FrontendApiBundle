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

namespace CoreShop\Bundle\ApiBundle\ViewFactory\Cart;

use CoreShop\Bundle\ApiBundle\ViewFactory\Product\ProductViewFactoryInterface;
use CoreShop\Bundle\ApiBundle\ViewModel\Cart\CartItemView;
use CoreShop\Component\Order\Model\CartItemInterface;

final class CartItemViewFactory implements CartItemViewFactoryInterface
{
    /**
     * @var string
     */
    private $cartItemViewClass;

    /**
     * @var ProductViewFactoryInterface
     */
    private $productViewFactory;

    public function __construct(
        string $cartItemViewClass,
        ProductViewFactoryInterface $productViewFactory
    ) {
        $this->cartItemViewClass = $cartItemViewClass;
        $this->productViewFactory = $productViewFactory;
    }

    /** {@inheritdoc} */
    public function create(CartItemInterface $item, string $locale): CartItemView
    {
        /**
         * @var CartItemView $itemView
         */
        $itemView = new $this->cartItemViewClass();

        $itemView->id = $item->getId();
        $itemView->quantity = $item->getQuantity();
        $itemView->total = $item->getTotal();
        $itemView->product = $this->productViewFactory->create($item->getProduct(), $item->getCart()->getStore(), $locale);

        return $itemView;
    }
}
