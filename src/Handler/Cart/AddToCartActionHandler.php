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

namespace CoreShop\Bundle\ApiBundle\Handler\Cart;

use ApiPlatform\Core\Bridge\Symfony\Validator\Exception\ValidationException;
use CoreShop\Bundle\ApiBundle\Action\Cart\AddToCartAction;
use CoreShop\Bundle\ApiBundle\ViewFactory\Cart\CartViewFactoryInterface;
use CoreShop\Bundle\OrderBundle\Factory\AddToCartFactoryInterface;
use CoreShop\Bundle\OrderBundle\Form\Type\AddToCartType;
use CoreShop\Component\Order\Factory\CartItemFactoryInterface;
use CoreShop\Component\Order\Manager\CartManagerInterface;
use CoreShop\Component\StorageList\StorageListModifierInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Validator\ConstraintViolationList;

class AddToCartActionHandler implements MessageHandlerInterface
{
    /**
     * @var CartItemFactoryInterface
     */
    protected $cartItemFactory;

    /**
     * @var AddToCartFactoryInterface
     */
    protected $addToCartFactory;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var StorageListModifierInterface
     */
    protected $cartModifier;

    /**
     * @var CartManagerInterface
     */
    protected $cartManager;

    /**
     * @var CartViewFactoryInterface
     */
    protected $cartViewFactory;

    /**
     * @param CartItemFactoryInterface     $cartItemFactory
     * @param AddToCartFactoryInterface    $addToCartFactory
     * @param FormFactoryInterface         $formFactory
     * @param StorageListModifierInterface $cartModifier
     * @param CartManagerInterface         $cartManager
     * @param CartViewFactoryInterface     $cartViewFactory
     */
    public function __construct(
        CartItemFactoryInterface $cartItemFactory,
        AddToCartFactoryInterface $addToCartFactory,
        FormFactoryInterface $formFactory,
        StorageListModifierInterface $cartModifier,
        CartManagerInterface $cartManager,
        CartViewFactoryInterface $cartViewFactory
    ) {
        $this->cartItemFactory = $cartItemFactory;
        $this->addToCartFactory = $addToCartFactory;
        $this->formFactory = $formFactory;
        $this->cartModifier = $cartModifier;
        $this->cartManager = $cartManager;
        $this->cartViewFactory = $cartViewFactory;
    }

    public function __invoke(AddToCartAction $addToCartAction)
    {
        $cartItem = $this->cartItemFactory->createWithPurchasable($addToCartAction->getProduct(), $addToCartAction->getQuantity());
        $addToCart = $this->addToCartFactory->createWithCartAndCartItem($addToCartAction->getCart(), $cartItem);

        $form = $this->formFactory->create(AddToCartType::class, $addToCart, [
            'csrf_protection' => false
        ]);

        $form->submit(array_merge(['cartItem' => ['quantity' => $addToCartAction->getQuantity()], $addToCartAction->getOptions()]));

        if (!$form->isValid()) {
            throw new ValidationException(new ConstraintViolationList());
        }

        $addToCart = $form->getData();

        $this->cartModifier->addToList($addToCart->getCart(), $addToCart->getCartItem());
        $this->cartManager->persistCart($addToCart->getCart());

        return $this->cartViewFactory->create($addToCart->getCart(), 'de');
    }
}
