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

namespace CoreShop\Bundle\ApiBundle\ApiPlatform\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use CoreShop\Bundle\ApiBundle\Action\Cart\AddToCartAction;
use CoreShop\Bundle\ApiBundle\Action\Cart\AddToCartActionInput;
use CoreShop\Bundle\ApiBundle\Action\Cart\AddToCartGraphQlActionInput;
use CoreShop\Component\Order\Model\CartInterface;
use CoreShop\Component\Order\Model\PurchasableInterface;
use CoreShop\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

class AddToCartInputTransformer implements DataTransformerInterface
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var RepositoryInterface
     */
    protected $cartRepository;

    /**
     * @var RepositoryInterface
     */
    protected $purchasableRepository;

    /**
     * @param RequestStack $requestStack
     * @param RepositoryInterface $cartRepository
     * @param RepositoryInterface $purchasableRepository
     */
    public function __construct(
        RequestStack $requestStack,
        RepositoryInterface $cartRepository,
        RepositoryInterface $purchasableRepository
    ) {
        $this->requestStack = $requestStack;
        $this->cartRepository = $cartRepository;
        $this->purchasableRepository = $purchasableRepository;
    }


    public function transform($object, string $to, array $context = [])
    {
        /**
         * @var AddToCartActionInput $object
         */
        Assert::isInstanceOf($object, AddToCartActionInput::class);

        if ($object instanceof AddToCartGraphQlActionInput) {
            $cartId = $object->cartId;
        }
        else {
            $cartId = $this->requestStack->getCurrentRequest()->get('id');
        }

        $cart = $this->cartRepository->find($cartId);

        if (!$cart instanceof CartInterface) {
            throw new NotFoundHttpException();
        }

        $product = $this->purchasableRepository->find($object->product);

        if (!$product instanceof PurchasableInterface) {
            throw new NotFoundHttpException();
        }

        return new AddToCartAction(
            $cart,
            $product,
            $object->quantity,
            $object->options ?? []
        );
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof AddToCartActionInput) {
            return false;
        }

        return AddToCartAction::class === $to && null !== ($context['input']['class'] ?? null);
    }
}
