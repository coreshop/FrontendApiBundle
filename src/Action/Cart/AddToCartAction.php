<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2020 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Bundle\ApiBundle\Action\Cart;

use CoreShop\Component\Order\Model\CartInterface;
use CoreShop\Component\Order\Model\PurchasableInterface;

class AddToCartAction
{
    /**
     * @var CartInterface
     */
    private $cart;

    /**
     * @var PurchasableInterface
     */
    private $product;

    /**
     * @var float
     */
    private $quantity;

    /**
     * @var array
     */
    private $options;

    /**
     * @param CartInterface        $cart
     * @param PurchasableInterface $product
     * @param float                $quantity
     * @param array                $options
     */
    public function __construct(CartInterface $cart, PurchasableInterface $product, float $quantity, array $options)
    {
        $this->cart = $cart;
        $this->product = $product;
        $this->quantity = $quantity;
        $this->options = $options;
    }

    /**
     * @return CartInterface
     */
    public function getCart(): CartInterface
    {
        return $this->cart;
    }

    /**
     * @return PurchasableInterface
     */
    public function getProduct(): PurchasableInterface
    {
        return $this->product;
    }

    /**
     * @return float
     */
    public function getQuantity(): float
    {
        return $this->quantity;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }
}
