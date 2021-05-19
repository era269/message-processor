<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits;

use Era269\MessageProcessor\Message\EventInterface;
use Era269\MessageProcessor\MethodMap\OneOrLessMethodMapDecorator;
use Era269\MessageProcessor\MethodMap\OneOrMoreMethodMapDecorator;
use Era269\MethodMap\ClassNameMethodMap;
use Era269\MethodMap\MethodMapCacheDecorator;
use Era269\MethodMap\MethodMapInterface;
use Psr\SimpleCache\CacheInterface;
use ReflectionMethod;

trait CanGetMethodNameByEventTrait
{
    /**
     * @var MethodMapInterface
     */
    private $applyEventMap;

    /**
     * @param ClassNameMethodMap $applyEventMap
     */
    public function setApplyEventMap(MethodMapInterface $applyEventMap): void
    {
        $this->applyEventMap = $this->getDecoratedByExactlyOneMethodMap($applyEventMap);
    }

    private function getApplyEventMethodName(EventInterface $event): string
    {
        return current(
            $this->getApplyEventMap()->getMethodNames($event)
        ) ?: '';
    }

    private function getApplyEventMap(): MethodMapInterface
    {
        if (!isset($this->applyEventMap)) {
            $this->applyEventMap =
                new MethodMapCacheDecorator(
                    $this->getDecoratedByExactlyOneMethodMap(
                        new ClassNameMethodMap(
                            static::class,
                            ReflectionMethod::IS_PROTECTED
                        )
                    ),
                    $this->getCache(),
                    static::class
                );
        }

        return $this->applyEventMap;
    }

    private function getDecoratedByExactlyOneMethodMap(MethodMapInterface $methodMap): MethodMapInterface
    {
        return new OneOrMoreMethodMapDecorator(
            new OneOrLessMethodMapDecorator(
                $methodMap
            )
        );
    }

    abstract protected function getCache(): CacheInterface;
}
