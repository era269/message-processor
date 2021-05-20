<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits\Aware;

use Era269\MessageProcessor\Exception\ParameterIsNotSetLogicException;
use Psr\EventDispatcher\EventDispatcherInterface;

trait EventDispatcherAwareTrait
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher): void
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @return EventDispatcherInterface
     */
    protected function getEventDispatcher(): EventDispatcherInterface
    {
        if (!isset($this->eventDispatcher)) {
            throw new ParameterIsNotSetLogicException(static::class, 'eventDispatcher');
        }
        return $this->eventDispatcher;
    }
}
