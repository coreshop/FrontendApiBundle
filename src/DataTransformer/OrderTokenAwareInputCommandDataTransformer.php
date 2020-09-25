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

declare(strict_types=1);

namespace CoreShop\Bundle\ApiBundle\DataTransformer;

use CoreShop\Bundle\ApiBundle\Command\OrderTokenAwareInterface;
use CoreShop\Component\Core\Model\OrderInterface;

final class OrderTokenAwareInputCommandDataTransformer implements CommandDataTransformerInterface
{
    public function transform($object, string $to, array $context = [])
    {
        /** @var OrderInterface $cart */
        $cart = $context['object_to_populate'];

        $object->setOrderToken($cart->getToken());

        return $object;
    }

    public function supportsTransformation($object): bool
    {
        return $object instanceof OrderTokenAwareInterface;
    }
}
