<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits;

use Era269\MessageProcessor\MessageInterface;
use Era269\MessageProcessor\MethodMap\ExcludeMethodMapDecorator;
use Era269\MessageProcessor\MethodMap\OneOrLessMethodMapDecorator;
use Era269\MessageProcessor\MethodMap\OneOrMoreMethodMapDecorator;
use Era269\MethodMap\ClassNameMethodMap;
use Era269\MethodMap\InterfaceMethodMap;
use Era269\MethodMap\MethodMapCacheDecorator;
use Era269\MethodMap\MethodMapCollectionDecorator;
use Era269\MethodMap\MethodMapInterface;
use Psr\SimpleCache\CacheInterface;

trait CanGetMethodNameByMessageTrait
{
    /**
     * @var MethodMapInterface
     */
    private $methodMap;

    /**
     * @param MethodMapInterface $methodMap
     */
    public function setMethodMap(MethodMapInterface $methodMap): void
    {
        $this->methodMap = $this->getDecoratedByExactlyOneMethodExcludingProcessMethodMap(
            $methodMap
        );
    }

    private function getDecoratedByExactlyOneMethodExcludingProcessMethodMap(MethodMapInterface $methodMap): MethodMapInterface
    {
        return new OneOrMoreMethodMapDecorator(
            new OneOrLessMethodMapDecorator(
                new ExcludeMethodMapDecorator(
                    $methodMap,
                    ['process']
                )
            )
        );
    }

    private function getMethodName(MessageInterface $message): string
    {
        return current(
            $this->getMethodMap()->getMethodNames($message)
        );
    }

    private function getMethodMap(): MethodMapInterface
    {
        if (!isset($this->methodMap)) {
            $this->methodMap =
                new MethodMapCacheDecorator(
                    $this->getDecoratedByExactlyOneMethodExcludingProcessMethodMap(
                        new MethodMapCollectionDecorator(
                            new ClassNameMethodMap(static::class),
                            new InterfaceMethodMap(static::class)
                        )
                    ),
                    $this->getCache(),
                    static::class
                );
        }

        return $this->methodMap;
    }

    abstract protected function getCache(): CacheInterface;
}
