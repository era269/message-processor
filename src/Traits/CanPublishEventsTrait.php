<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits;

use Era269\MessageProcessor\Traits\Aware\EventDispatcherAwareTrait;

trait CanPublishEventsTrait
{
    use EventDispatcherAwareTrait;

    protected function publish(object ...$events): void
    {
        foreach ($events as $event) {
            $this->publishThat($event);
        }
    }

    protected function publishThat(object $event): void
    {
        $this->getEventDispatcher()->dispatch($event);
    }
}
