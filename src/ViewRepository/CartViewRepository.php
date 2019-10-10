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

namespace CoreShop\Bundle\ApiBundle\ViewRepository;

use CoreShop\Bundle\ApiBundle\ViewFactory\Cart\CartViewFactoryInterface;
use CoreShop\Bundle\ApiBundle\ViewModel\Cart\CartSummaryView;
use CoreShop\Component\Order\Repository\CartRepositoryInterface;
use Webmozart\Assert\Assert;

class CartViewRepository implements CartViewRepositoryInterface
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * @var CartViewFactoryInterface
     */
    private $cartViewFactory;

    public function __construct(
        CartRepositoryInterface $cartRepository,
        CartViewFactoryInterface $cartViewFactory
    ) {
        $this->cartRepository = $cartRepository;
        $this->cartViewFactory = $cartViewFactory;
    }

    public function getById(int $id): CartSummaryView
    {
        $cart = $this->cartRepository->findCartById($id);

        Assert::notNull($cart, 'Cart with given id does not exists');

        return $this->cartViewFactory->create($cart, $cart->getLocaleCode());
    }
}
