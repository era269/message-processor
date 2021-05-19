<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits;

use Era269\MessageProcessor\Message\EventInterface;

trait CanApplyPrivateEventsTrait
{
    use CanGetMethodNameByEventTrait;

    protected function apply(EventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->applyThat($event);
        }
    }

    private function applyThat(EventInterface $event): void
    {
        $methodName = $this->getApplyEventMethodName($event);
        $this->$methodName($event);
    }
}
