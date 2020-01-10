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

namespace CoreShop\Bundle\ApiBundle\ViewModel;

class ProductView
{
    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $shortDescription;

    /**
     * @var array
     */
    public $images = [];

    /**
     * @var PriceView
     */
    public $retailPrice;

    /**
     * @var PriceView
     */
    public $discountPrice;

    /**
     * @var PriceView
     */
    public $discount;

    /**
     * @var PriceView
     */
    public $price;
}
