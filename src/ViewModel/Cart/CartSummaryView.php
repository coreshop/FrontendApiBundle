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

namespace CoreShop\Bundle\ApiBundle\ViewModel\Cart;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class CartSummaryView
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $store;

    /**
     * @var string
     */
    public $currency;

    /**
     * @var string
     */
    public $locale;

    /**
     * @var CartItemView[]|ArrayCollection
     */
    public $items = [];
}
