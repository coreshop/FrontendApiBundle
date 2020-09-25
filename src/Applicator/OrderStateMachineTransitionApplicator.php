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

namespace CoreShop\Bundle\ApiBundle\Applicator;

use CoreShop\Bundle\WorkflowBundle\Manager\StateMachineManagerInterface;
use CoreShop\Component\Core\Model\OrderInterface;
use CoreShop\Component\Order\OrderTransitions;

final class OrderStateMachineTransitionApplicator implements OrderStateMachineTransitionApplicatorInterface
{
    private $stateMachineFactory;

    public function __construct(StateMachineManagerInterface $stateMachineFactory)
    {
        $this->stateMachineFactory = $stateMachineFactory;
    }

    public function cancel(OrderInterface $data): OrderInterface
    {
        $this->applyTransition($data, OrderTransitions::TRANSITION_CANCEL);

        return $data;
    }

    private function applyTransition(OrderInterface $order, string $transition): void
    {
        $workflow = $this->stateMachineFactory->get($order, OrderTransitions::IDENTIFIER);
        $workflow->apply($order, $transition);
    }
}
