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

use CoreShop\Bundle\ApiBundle\ViewFactory\Product\ProductViewFactoryInterface;
use CoreShop\Bundle\ApiBundle\ViewModel\ProductView;
use CoreShop\Component\Resource\Repository\RepositoryInterface;
use CoreShop\Component\Store\Context\StoreContextInterface;
use Webmozart\Assert\Assert;

class ProductViewRepository implements ProductViewRepositoryInterface
{
    /**
     * @var RepositoryInterface
     */
    private $purchasableRepository;

    /**
     * @var ProductViewFactoryInterface
     */
    private $productVieWFactory;

    /**
     * @var StoreContextInterface
     */
    private $storeContext;

    /**
     * @param RepositoryInterface         $purchasableRepository
     * @param ProductViewFactoryInterface $productVieWFactory
     * @param StoreContextInterface       $storeContext
     */
    public function __construct(
        RepositoryInterface $purchasableRepository,
        ProductViewFactoryInterface $productVieWFactory,
        StoreContextInterface $storeContext
    ) {
        $this->purchasableRepository = $purchasableRepository;
        $this->productVieWFactory = $productVieWFactory;
        $this->storeContext = $storeContext;
    }

    public function getById(int $id): ProductView
    {
        $product = $this->purchasableRepository->find($id);

        Assert::notNull($product, 'Product with given id does not exists');

        return $this->productVieWFactory->create($product, $this->storeContext->getStore(), 'de');
    }
}
