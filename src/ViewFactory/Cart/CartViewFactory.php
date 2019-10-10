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

use CoreShop\Bundle\ApiBundle\ViewModel\Cart\CartSummaryView;
use CoreShop\Component\Core\Model\CartItemInterface;
use CoreShop\Component\Order\Model\CartInterface;

final class CartViewFactory implements CartViewFactoryInterface
{
    /**
     * @var string
     */
    private $cartSummaryViewClass;

    /**
     * @var CartItemViewFactoryInterface
     */
    private $cartItemFactory;

    public function __construct(
        string $cartSummaryViewClass,
        CartItemViewFactoryInterface $cartItemFactory
    ) {
        $this->cartSummaryViewClass = $cartSummaryViewClass;
        $this->cartItemFactory = $cartItemFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function create(CartInterface $cart, string $localeCode): CartSummaryView
    {
        /** @var CartSummaryView $cartView */
        $cartView = new $this->cartSummaryViewClass();
        $cartView->id = $cart->getId();
        $cartView->store = $cart->getStore()->getId();
        $cartView->currency = $cart->getCurrency()->getIsoCode();
        $cartView->locale = $localeCode;

        /**
         * @var CartItemInterface $item
         */
        foreach ($cart->getItems() as $item) {
            $cartView->items[] = $this->cartItemFactory->create($item, $localeCode);
        }

        return $cartView;
    }
}
