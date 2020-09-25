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

use CoreShop\Bundle\ApiBundle\Command\Cart\PickupCart;
use CoreShop\Bundle\ApiBundle\Context\UserContextInterface;
use CoreShop\Component\Core\Model\CustomerInterface;
use CoreShop\Component\Core\Model\OrderInterface;
use CoreShop\Component\Core\Model\StoreInterface;
use CoreShop\Component\Order\Manager\CartManagerInterface;
use CoreShop\Component\Resource\Factory\FactoryInterface;
use CoreShop\Component\Resource\TokenGenerator\UniqueTokenGenerator;
use CoreShop\Component\Store\Context\StoreContextInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class PickupCartHandler implements MessageHandlerInterface
{
    /** @var FactoryInterface */
    private $cartFactory;
    private $storeContext;
    private $userContext;
    private $cartManager;
    private $generator;

    public function __construct(
        FactoryInterface $cartFactory,
        StoreContextInterface $storeContext,
        UserContextInterface $userContext,
        CartManagerInterface $cartManager
    ) {
        $this->cartFactory = $cartFactory;
        $this->storeContext = $storeContext;
        $this->userContext = $userContext;
        $this->cartManager = $cartManager;
        $this->generator = new UniqueTokenGenerator();
    }

    public function __invoke(PickupCart $pickupCart)
    {
        /** @var OrderInterface $cart */
        $cart = $this->cartFactory->createNew();

        /** @var StoreInterface $channel */
        $channel = $this->storeContext->getStore();
//        /** @var LocaleInterface $locale */
//        $locale = $channel->getDefaultLocale();
        /** @var StoreInterface $currency */
        $currency = $channel->getCurrency();
        /** @var UserInterface|null $user */
        $user = $this->userContext->getUser();

        if ($user !== null && $user instanceof CustomerInterface) {
            $cart->setCustomer($user);
        }

        $cart->setStore($channel);
        //$cart->setLocaleCode($locale->getCode());
        $cart->setCurrency($currency);
        $cart->setToken($pickupCart->tokenValue ?? $this->generator->generate(10));

        $this->cartManager->persistCart($cart);

        return $cart;
    }
}
