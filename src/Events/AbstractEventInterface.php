<?php
/**
 * This file is part of Ar3s/exchange1c package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Ar3s\Exchange1C\Events;

use Ar3s\Exchange1C\Interfaces\EventInterface;

/**
 * Class AbstractEventInterface.
 */
abstract class AbstractEventInterface implements EventInterface
{
    public const NAME = self::class;

    /**
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }
}
