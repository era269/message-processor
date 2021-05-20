<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Tests\Traits\Aware;

use Era269\MessageProcessor\Exception\ParameterIsNotSetLogicException;
use Era269\MessageProcessor\Traits\Aware\EventDispatcherAwareTrait;
use PHPUnit\Framework\TestCase;

class EventDispatcherAwareTraitTest extends TestCase
{
    public function testGetFail(): void
    {
        $object = new class {
            use EventDispatcherAwareTrait;

            public function getFail(): object
            {
                return $this->getEventDispatcher();
            }
        };
        $this->expectException(ParameterIsNotSetLogicException::class);
        $object->getFail();
    }
}
