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

namespace CoreShop\Bundle\ApiBundle\ApiPlatform\SubresourceDataProvider\Cart;

use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\DataProvider\SubresourceDataProviderInterface;
use CoreShop\Bundle\ApiBundle\ViewFactory\Cart\CartItemViewFactoryInterface;
use CoreShop\Bundle\ApiBundle\ViewModel\Cart\CartItemView;
use CoreShop\Bundle\ApiBundle\ViewRepository\CartItemViewRepositoryInterface;
use CoreShop\Component\Order\Model\CartInterface;
use CoreShop\Component\Order\Repository\CartRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CartItemsSubresourceViewProvider implements SubresourceDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * @var CartRepositoryInterface
     */
    private $repository;

    /**
     * @var CartItemViewFactoryInterface
     */
    private $cartItemViewFactory;

    public function __construct(
        CartRepositoryInterface $repository,
        CartItemViewFactoryInterface $cartItemViewFactory
    ) {
        $this->repository = $repository;
        $this->cartItemViewFactory = $cartItemViewFactory;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === CartItemView::class && $context['collection'];
    }

    public function getSubresource(
        string $resourceClass,
        array $identifiers,
        array $context,
        string $operationName = null
    ) {
        $cart = $this->repository->find($identifiers['id']);

        if (!$cart instanceof CartInterface) {
            throw new NotFoundHttpException();
        }

        foreach ($cart->getItems() as $item) {
            yield $this->cartItemViewFactory->create($item, $cart->getLocaleCode());
        }
    }
}
