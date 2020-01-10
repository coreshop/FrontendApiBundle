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

namespace CoreShop\Bundle\ApiBundle\PropertyInfo;

use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\PropertyInfo\Type;

final class PhpDocExtractor implements PropertyTypeExtractorInterface
{
    /**
     * @var PropertyTypeExtractorInterface
     */
    private $decorated;

    /**
     * @param PropertyTypeExtractorInterface $decorated
     */
    public function __construct(PropertyTypeExtractorInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function getTypes($class, $property, array $context = [])
    {
        $types = $this->decorated->getTypes($class, $property, $context);

        if (is_array($types) && count($types) === 2) {
            $type1 = $types[0];
            $type2 = $types[1];

            if ($type1->getBuiltinType() === Type::BUILTIN_TYPE_ARRAY && $type2->getBuiltinType() === Type::BUILTIN_TYPE_OBJECT) {
                return [new Type(
                    Type::BUILTIN_TYPE_OBJECT,
                    $type2->isNullable(),
                    $type1->getClassName(),
                    true,
                    $type1->getCollectionKeyType(),
                    $type1->getCollectionValueType()
                )];
            }

            if ($type1->getBuiltinType() === Type::BUILTIN_TYPE_OBJECT && $type2->getBuiltinType() === Type::BUILTIN_TYPE_ARRAY) {
                return [new Type(
                    Type::BUILTIN_TYPE_OBJECT,
                    $type1->isNullable(),
                    $type2->getClassName(),
                    true,
                    $type2->getCollectionKeyType(),
                    $type2->getCollectionValueType()
                )];
            }
        }

        return $types;
    }
}

