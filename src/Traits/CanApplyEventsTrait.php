<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits;

use Era269\MessageProcessor\Message\EventInterface;
use Era269\MessageProcessor\Traits\Aware\ApplyEventMethodMapAwareTrait;

trait CanApplyEventsTrait
{
    use ApplyEventMethodMapAwareTrait;

    protected function apply(EventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->applyThat($event);
        }
    }

    private function applyThat(EventInterface $event): void
    {
        $methodNames = $this->getApplyEventMethodMap()
            ->getMethodNames($event);
        foreach ($methodNames as $methodName) {
            $this->{$methodName}($event);
        }
    }
}
