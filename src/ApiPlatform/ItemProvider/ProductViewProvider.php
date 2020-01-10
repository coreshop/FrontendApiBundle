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

namespace CoreShop\Bundle\ApiBundle\ApiPlatform\ItemProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use CoreShop\Bundle\ApiBundle\ViewModel\ProductView;
use CoreShop\Bundle\ApiBundle\ViewRepository\ProductViewRepositoryInterface;

class ProductViewProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @var ProductViewRepositoryInterface
     */
    private $repository;

    /**
     * @param ProductViewRepositoryInterface $repository
     */
    public function __construct(ProductViewRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === ProductView::class;
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = [])
    {
        return $this->repository->getById($id);
    }
}
