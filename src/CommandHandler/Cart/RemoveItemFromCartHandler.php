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

use CoreShop\Bundle\ApiBundle\Command\Cart\RemoveItemFromCart;
use CoreShop\Component\Core\Model\OrderInterface;
use CoreShop\Component\Core\Model\OrderItemInterface;
use CoreShop\Component\Order\Cart\CartModifierInterface;
use CoreShop\Component\Order\Manager\CartManagerInterface;
use CoreShop\Component\Order\Repository\OrderItemRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class RemoveItemFromCartHandler implements MessageHandlerInterface
{
    private $orderItemRepository;
    private $orderModifier;
    private $cartManager;

    public function __construct(
        OrderItemRepositoryInterface $orderItemRepository,
        CartModifierInterface $orderModifier,
        CartManagerInterface  $cartManager
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->orderModifier = $orderModifier;
        $this->cartManager = $cartManager;
    }

    public function __invoke(RemoveItemFromCart $removeItemFromCart): OrderInterface
    {
        /** @var OrderItemInterface $orderItem */
        $orderItem = $this->orderItemRepository->find($removeItemFromCart->orderItemId);

        Assert::notNull($orderItem);

        /** @var OrderInterface $cart */
        $cart = $orderItem->getOrder();

        Assert::same($cart->getToken(), $removeItemFromCart->orderToken);

        $this->orderModifier->removeFromList($cart, $orderItem);

        $this->cartManager->persistCart($cart);

        return $cart;
    }
}
