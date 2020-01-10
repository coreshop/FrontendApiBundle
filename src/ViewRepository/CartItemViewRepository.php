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

namespace CoreShop\Bundle\ApiBundle\ViewRepository;

use CoreShop\Bundle\ApiBundle\ViewFactory\Cart\CartItemViewFactoryInterface;
use CoreShop\Bundle\ApiBundle\ViewModel\Cart\CartItemView;
use CoreShop\Component\Order\Repository\CartItemRepositoryInterface;
use Webmozart\Assert\Assert;

class CartItemViewRepository implements CartItemViewRepositoryInterface
{
    /**
     * @var CartItemRepositoryInterface
     */
    private $cartItemRepository;

    /**
     * @var CartItemViewFactoryInterface
     */
    private $cartItemViewFactory;

    public function __construct(
        CartItemRepositoryInterface $cartItemRepository,
        CartItemViewFactoryInterface $cartItemViewFactory
    ) {
        $this->cartItemRepository = $cartItemRepository;
        $this->cartItemViewFactory = $cartItemViewFactory;
    }

    public function getById(int $id): CartItemView
    {
        $cartItem = $this->cartItemRepository->find($id);

        Assert::notNull($cartItem, 'Cart Item with given id does not exists');

        return $this->cartItemViewFactory->create($cartItem, $cartItem->getCart()->getLocaleCode());
    }
}
