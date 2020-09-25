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

namespace CoreShop\Bundle\ApiBundle\Context;

use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Order\Context\CartContextInterface;
use CoreShop\Component\Order\Context\CartNotFoundException;
use CoreShop\Component\Order\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class TokenValueBasedCartContext implements CartContextInterface
{
    private $requestStack;
    private $orderRepository;
    private $newApiRoute;

    public function __construct(
        RequestStack $requestStack,
        OrderRepositoryInterface $orderRepository,
        string $newApiRoute
    ) {
        $this->requestStack = $requestStack;
        $this->orderRepository = $orderRepository;
        $this->newApiRoute = $newApiRoute;
    }

    public function getCart(): OrderInterface
    {
        $request = $this->getMasterRequest();
        $this->checkApiRequest($request);

        $tokenValue = $request->attributes->get('id');
        if ($tokenValue === null) {
            throw new CartNotFoundException('CoreShop was not able to find the cart, as there is no passed token value.');
        }

        $cart = $this->orderRepository->findOneBy(['token' => $tokenValue]);

        if (null === $cart) {
            throw new CartNotFoundException('CoreShop was not able to find the cart for passed token value.');
        }

        return $cart;
    }

    private function getMasterRequest(): Request
    {
        $masterRequest = $this->requestStack->getMasterRequest();
        if (null === $masterRequest) {
            throw new \UnexpectedValueException('There is no master request on request stack.');
        }

        return $masterRequest;
    }

    private function checkApiRequest(Request $request): void
    {
        if (strpos($request->getRequestUri(), $this->newApiRoute) === false) {
            throw new CartNotFoundException('The master request is not an API request.');
        }
    }
}
