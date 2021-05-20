<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Tests\Traits\Aware;

use Era269\MessageProcessor\Exception\ParameterIsNotSetLogicException;
use Era269\MessageProcessor\Traits\Aware\ProcessMessageMethodMapAwareTrait;
use PHPUnit\Framework\TestCase;

class ProcessMessageMethodMapAwareTraitTest extends TestCase
{
    public function testGetFail(): void
    {
        $object = new class {
            use ProcessMessageMethodMapAwareTrait;

            public function getFail(): object
            {
                return $this->getProcessMessageMethodMap();
            }
        };
        $this->expectException(ParameterIsNotSetLogicException::class);
        $object->getFail();
    }
}
