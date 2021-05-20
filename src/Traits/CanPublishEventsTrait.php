<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits;

use Era269\MessageProcessor\Message\EventInterface;
use Era269\MessageProcessor\Traits\Aware\EventDispatcherAwareTrait;

trait CanPublishEventsTrait
{
    use EventDispatcherAwareTrait;

    protected function publish(EventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->publishThat($event);
        }
    }

    /**
     * @param object $event
     */
    protected function publishThat($event): void
    {
        $this->getEventDispatcher()->dispatch($event);
    }
}
