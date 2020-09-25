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

use CoreShop\Bundle\ApiBundle\Command\Cart\ChangeItemQuantityInCart;
use CoreShop\Component\Core\Model\OrderInterface;
use CoreShop\Component\Core\Model\OrderItemInterface;
use CoreShop\Component\Core\Order\Modifier\CartItemQuantityModifier;
use CoreShop\Component\Order\Manager\CartManagerInterface;
use CoreShop\Component\Order\Repository\OrderItemRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class ChangeItemQuantityInCartHandler implements MessageHandlerInterface
{
    private $orderItemRepository;
    private $orderItemQuantityModifier;
    private $cartManager;

    public function __construct(
        OrderItemRepositoryInterface $orderItemRepository,
        CartItemQuantityModifier $orderItemQuantityModifier,
        CartManagerInterface $cartManager
    ) {
        $this->orderItemRepository = $orderItemRepository;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
        $this->cartManager = $cartManager;
    }

    public function __invoke(ChangeItemQuantityInCart $command): OrderInterface
    {
        /** @var OrderItemInterface $orderItem */
        $orderItem = $this->orderItemRepository->find($command->orderItemId);

        Assert::notNull($orderItem);

        /** @var OrderInterface $cart */
        $cart = $orderItem->getOrder();

        Assert::same($cart->getToken(), $command->orderToken);

        $this->orderItemQuantityModifier->modify($orderItem, $command->quantity);
        $this->cartManager->persistCart($cart);

        return $cart;
    }
}
