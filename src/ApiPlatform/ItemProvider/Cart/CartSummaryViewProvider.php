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

namespace CoreShop\Bundle\ApiBundle\ApiPlatform\ItemProvider\Cart;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use CoreShop\Bundle\ApiBundle\Request\Cart\SummarizeCartRequest;
use CoreShop\Bundle\ApiBundle\ViewModel\Cart\CartSummaryView;
use CoreShop\Bundle\ApiBundle\ViewRepository\CartViewRepositoryInterface;

class CartSummaryViewProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @var CartViewRepositoryInterface
     */
    private $repository;

    /**
     * @param CartViewRepositoryInterface $repository
     */
    public function __construct(CartViewRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === CartSummaryView::class;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->repository->getById($id);
    }
}
