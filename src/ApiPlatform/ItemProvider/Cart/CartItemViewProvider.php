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

namespace CoreShop\Bundle\ApiBundle\ApiPlatform\ItemProvider\Cart;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use CoreShop\Bundle\ApiBundle\ViewModel\Cart\CartItemView;
use CoreShop\Bundle\ApiBundle\ViewRepository\CartItemViewRepositoryInterface;
use CoreShop\Component\Order\Repository\CartItemRepositoryInterface;

class CartItemViewProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @var CartItemViewRepositoryInterface
     */
    private $repository;

    /**
     * @param CartItemViewRepositoryInterface $repository
     */
    public function __construct(CartItemViewRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === CartItemView::class;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->repository->getById($id);
    }
}
