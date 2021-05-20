<?php

declare(strict_types=1);

namespace Era269\MessageProcessor;

use Cache\Adapter\PHPArray\ArrayCachePool;
use Era269\MessageProcessor\Message\EventInterface;
use Era269\MessageProcessor\MethodMap\ExcludeMethodMapDecorator;
use Era269\MessageProcessor\MethodMap\OneOrLessMethodMapDecorator;
use Era269\MessageProcessor\MethodMap\OneOrMoreMethodMapDecorator;
use Era269\MessageProcessor\Traits\Aware\CacheAwareTrait;
use Era269\MessageProcessor\Traits\CanApplyEventsTrait;
use Era269\MessageProcessor\Traits\CanProcessMessage;
use Era269\MessageProcessor\Traits\CanPublishEventsTrait;
use Era269\MethodMap\ClassNameMethodMap;
use Era269\MethodMap\InterfaceMethodMap;
use Era269\MethodMap\MethodMapCacheDecorator;
use Era269\MethodMap\MethodMapCollectionDecorator;
use Era269\MethodMap\MethodMapInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\SimpleCache\CacheInterface;
use ReflectionMethod;

abstract class AbstractMessageProcessor implements
    MessageProcessorInterface,
    CacheAwareInterface,
    EventDispatcherAwareInterface,
    ApplyEventMethodMapAwareInterface,
    ProcessMessageMethodMapAwareInterface
{
    use CacheAwareTrait;
    use CanProcessMessage;
    use CanApplyEventsTrait;
    use CanPublishEventsTrait;

    public function __construct(EventDispatcherInterface $eventDispatcher, ?MethodMapInterface $processMessageMethodMap = null, ?MethodMapInterface $applyEventMethodMap = null, ?CacheInterface $cache = null)
    {
        $this->setEventDispatcher($eventDispatcher);
        $this->setCache($cache ?? new ArrayCachePool());
        $this->setProcessMessageMethodMap(
            $processMessageMethodMap
            ?? new MethodMapCacheDecorator(
                new OneOrMoreMethodMapDecorator(
                    new OneOrLessMethodMapDecorator(
                        new ExcludeMethodMapDecorator(
                            new MethodMapCollectionDecorator(
                                new ClassNameMethodMap(static::class),
                                new InterfaceMethodMap(static::class)
                            ),
                            ['process']
                        )
                    )
                ),
                $this->getCache(),
                static::class
            )
        );
        $this->setApplyEventMethodMap(
            $applyEventMethodMap
            ?? new MethodMapCacheDecorator(
                new OneOrMoreMethodMapDecorator(
                    new OneOrLessMethodMapDecorator(
                        new ClassNameMethodMap(
                            static::class,
                            ReflectionMethod::IS_PROTECTED
                        )
                    )
                ),
                $this->getCache(),
                static::class
            )
        );
    }

    protected function applyAndPublish(EventInterface ...$events): void
    {
        foreach ($events as $event) {
            $this->applyThat($event);
            $this->publishThat($event);
        }
    }
}
