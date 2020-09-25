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

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use CoreShop\Bundle\ApiBundle\Command\CommandAwareDataTransformerInterface;

/** @experimental */
final class CommandAwareInputDataTransformer implements DataTransformerInterface
{
    /** @var CommandDataTransformerInterface[] */
    private $commandDataTransformers;

    public function __construct(CommandDataTransformerInterface ...$commandDataTransformers)
    {
        $this->commandDataTransformers = $commandDataTransformers;
    }

    public function transform($object, string $to, array $context = [])
    {
        foreach ($this->commandDataTransformers as $transformer) {
            if ($transformer->supportsTransformation($object)) {
                $object = $transformer->transform($object, $to, $context);
            }
        }

        return $object;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return is_a($context['input']['class'], CommandAwareDataTransformerInterface::class, true);
    }
}
