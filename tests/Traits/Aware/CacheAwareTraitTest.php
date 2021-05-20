<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Tests\Traits\Aware;

use Era269\MessageProcessor\Exception\ParameterIsNotSetLogicException;
use Era269\MessageProcessor\Traits\Aware\CacheAwareTrait;
use PHPUnit\Framework\TestCase;

class CacheAwareTraitTest extends TestCase
{
    public function testGetFail(): void
    {
        $object = new class {
            use CacheAwareTrait;

            public function getCacheFail(): object
            {
                return $this->getCache();
            }
        };
        $this->expectException(ParameterIsNotSetLogicException::class);
        $object->getCacheFail();
    }
}
