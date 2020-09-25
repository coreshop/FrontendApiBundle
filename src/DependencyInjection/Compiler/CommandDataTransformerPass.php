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

namespace CoreShop\Bundle\ApiBundle\DependencyInjection\Compiler;

use CoreShop\Bundle\ApiBundle\DataTransformer\CommandAwareInputDataTransformer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/** @experimental */
final class CommandDataTransformerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $commandDataTransformersChainDefinition = new Definition(CommandAwareInputDataTransformer::class);

        $taggedServices = $container->findTaggedServiceIds('coreshop.api.command_data_transformer');

        foreach ($taggedServices as $key => $value) {
            $commandDataTransformersChainDefinition->addArgument(new Reference($key));
        }

        $commandDataTransformersChainDefinition->addTag('api_platform.data_transformer');

        $container->setDefinition(
            'coreshop.api.data_transformer.command_aware_input_data_transformer',
            $commandDataTransformersChainDefinition
        );
    }
}
