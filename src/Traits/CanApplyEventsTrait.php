<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits;

use Era269\MessageProcessor\Traits\Aware\ApplyEventMethodMapAwareTrait;

trait CanApplyEventsTrait
{
    use ApplyEventMethodMapAwareTrait;

    protected function apply(object ...$events): void
    {
        foreach ($events as $event) {
            $this->applyThat($event);
        }
    }

    private function applyThat(object $event): void
    {
        $methodNames = $this->getApplyEventMethodMap()
            ->getMethodNames($event);
        foreach ($methodNames as $methodName) {
            $this->{$methodName}($event);
        }
    }
}
