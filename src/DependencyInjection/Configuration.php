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

namespace CoreShop\Bundle\ApiBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use CoreShop\Bundle\ApiBundle\ViewModel;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('coreshop_api');
        $rootNode = $treeBuilder->getRootNode();

        $this->buildViewClassesNode($rootNode);

        return $treeBuilder;
    }

    private function buildViewClassesNode(ArrayNodeDefinition $rootNode): void
    {
        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('view_classes')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('price')->defaultValue(ViewModel\PriceView::class)->end()
                        ->scalarNode('product')->defaultValue(ViewModel\ProductView::class)->end()
                        ->scalarNode('cart_item')->defaultValue(ViewModel\Cart\CartItemView::class)->end()
                        ->scalarNode('cart_summary')->defaultValue(ViewModel\Cart\CartSummaryView::class)->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
