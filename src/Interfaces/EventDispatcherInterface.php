<?php
/**
 * This file is part of Ar3s/exchange1c package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Ar3s\Exchange1C\Interfaces;

/**
 * Interface EventDispatcherInterface.
 */
interface EventDispatcherInterface
{
    /**
     * @param EventInterface $event
     */
    public function dispatch(EventInterface $event): void;
}
