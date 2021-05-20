<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Tests\Traits\Aware;

use Era269\MessageProcessor\Exception\ParameterIsNotSetLogicException;
use Era269\MessageProcessor\Traits\Aware\ApplyEventMethodMapAwareTrait;
use PHPUnit\Framework\TestCase;

class ApplyEventMethodMapAwareTraitTest extends TestCase
{
    public function testGetFail(): void
    {
        $object = new class {
            use ApplyEventMethodMapAwareTrait;
            public function getMap(): object
            {
                return $this->getApplyEventMethodMap();
            }
        };
        $this->expectException(ParameterIsNotSetLogicException::class);
        $object->getMap();
    }
}
