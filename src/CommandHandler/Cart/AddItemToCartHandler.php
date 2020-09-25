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

declare(strict_types=1);

namespace CoreShop\Bundle\ApiBundle\CommandHandler\Cart;

use CoreShop\Bundle\ApiBundle\Command\Cart\AddItemToCart;
use CoreShop\Component\Core\Model\OrderInterface;
use CoreShop\Component\Core\Model\OrderItemInterface;
use CoreShop\Component\Core\Order\Modifier\CartItemQuantityModifier;
use CoreShop\Component\Order\Cart\CartModifierInterface;
use CoreShop\Component\Order\Factory\OrderItemFactoryInterface;
use CoreShop\Component\Order\Manager\CartManagerInterface;
use CoreShop\Component\Order\Model\PurchasableInterface;
use CoreShop\Component\Order\Processor\CartProcessorInterface;
use CoreShop\Component\Order\Repository\OrderRepositoryInterface;
use CoreShop\Component\Resource\Repository\PimcoreRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class AddItemToCartHandler implements MessageHandlerInterface
{
    private $orderRepository;
    private $productRepository;
    private $orderModifier;
    private $cartItemFactory;
    private $orderItemQuantityModifier;
    private $cartManager;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        PimcoreRepositoryInterface $productRepository,
        CartModifierInterface $orderModifier,
        OrderItemFactoryInterface $cartItemFactory,
        CartItemQuantityModifier $orderItemQuantityModifier,
        CartManagerInterface $cartManager
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->orderModifier = $orderModifier;
        $this->cartItemFactory = $cartItemFactory;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
        $this->cartManager = $cartManager;
    }

    public function __invoke(AddItemToCart $addItemToCart): OrderInterface
    {
        /** @var PurchasableInterface $product */
        $product = $this->productRepository->find($addItemToCart->productId);

        Assert::notNull($product);

        /** @var OrderInterface $cart */
        $cart = $this->orderRepository->findOneBy(['token' => $addItemToCart->orderToken]);

        Assert::notNull($cart);

        /** @var OrderItemInterface $cartItem */
        $cartItem = $this->cartItemFactory->createWithCart($cart, $product, 0);
        $cartItem->setProduct($product);

        $this->orderItemQuantityModifier->modify($cartItem, $addItemToCart->quantity);
        $this->orderModifier->addToList($cart, $cartItem);

        $this->cartManager->persistCart($cart);

        return $cart;
    }
}
