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

namespace CoreShop\Bundle\ApiBundle\Security\Voter;

use CoreShop\Bundle\ApiBundle\ViewModel\Cart\CartSummaryView;
use CoreShop\Component\Core\Model\CustomerInterface;
use CoreShop\Component\Order\Model\CartInterface;
use CoreShop\Component\Order\Repository\CartRepositoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Webmozart\Assert\Assert;

class CartVoter extends Voter
{
    /**
     * @var CartRepositoryInterface
     */
    protected $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    protected function supports($attribute, $subject)
    {
        return $subject instanceof CartSummaryView;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        /**
         * @var CartSummaryView $subject
         */
        Assert::isInstanceOf($subject, CartSummaryView::class);

        /**
         * @var CartInterface $cart
         */
        $cart = $this->cartRepository->find($subject->id);
        $customer = $cart->getCustomer();
        $user = $token->getUser();

        if (!$user instanceof CustomerInterface && $customer instanceof CustomerInterface) {
            return false;
        }

        if ($user instanceof CustomerInterface && !$customer instanceof CustomerInterface) {
            return false;
        }

        if (!$user instanceof CustomerInterface && !$customer instanceof CustomerInterface) {
            return true;
        }

        if ($user->getId() !== $customer->getId()) {
            return false;
        }

        return true;
    }
}
