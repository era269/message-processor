<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits;

use Cache\Adapter\PHPArray\ArrayCachePool;
use Era269\MethodMap\ClassNameMethodMap;
use Era269\MethodMap\InterfaceMethodMap;
use Era269\MethodMap\MethodMapCacheDecorator;
use Era269\MethodMap\MethodMapCollectionDecorator;
use Era269\MessageProcessor\Message\NullMessage;
use Era269\MessageProcessor\MessageInterface;
use Era269\MethodMap\MethodMapInterface;
use LogicException;
use Psr\SimpleCache\CacheInterface;
use RuntimeException;

trait CanProcessMessageTrait
{
    /**
     * @var MethodMapInterface
     */
    private $methodMap;
    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @param MethodMapInterface $methodMap
     */
    public function setMethodMap(MethodMapInterface $methodMap): void
    {
        $this->methodMap = $methodMap;
    }

    public function setCache(CacheInterface $cache): void
    {
        $this->cache = $cache;
    }

    private function getCache(): CacheInterface
    {
        if (!isset($this->cache)) {
            $this->cache = new ArrayCachePool();
        }
        return $this->cache;
    }

    private function getMethodMap(): MethodMapInterface
    {
        if (!isset($this->methodMap)) {
            $this->methodMap = new MethodMapCacheDecorator(
                new MethodMapCollectionDecorator(
                    new ClassNameMethodMap(self::class),
                    new InterfaceMethodMap(self::class)
                ),
                $this->getCache(),
                self::class
            );
        }
        return $this->methodMap;
    }

    /**
     * @throws RuntimeException
     */
    final public function process(MessageInterface $message): MessageInterface
    {
        $methodName = $this->getMethodName($message);

        return $this->$methodName($message)
            ?? new NullMessage();
    }

    /**
     * @param string[] $methodNames
     */
    private function throwExceptionIfNoProcessorFound(array $methodNames, MessageInterface $message): void
    {
        if (empty($methodNames)) {
            throw new LogicException(sprintf(
                'Incorrect internal method call: "%s" doesn\'t know how to process the message "%s"',
                get_class($this),
                get_class($message)
            ));
        }
    }

    /**
     * @param string[] $methodNames
     */
    private function throwExceptionIfMoreThanOneProcessorFound(array $methodNames, MessageInterface $message): void
    {
        if (count($methodNames) > 1) {
            throw new LogicException(sprintf(
                'Controversial internal method call in "%s". More than ine method [%s] can process "%s"',
                get_class($this),
                implode(',', $methodNames),
                get_class($message)
            ));
        }
    }

    /**
     * @param MessageInterface $message
     *
     * @return string[]
     */
    private function getMethodNames(MessageInterface $message): array
    {
        $methodNames = $this->getMethodMap()->getMethodNames($message);

        return array_filter(
            $methodNames,
            function (string $item) {
                return 'process' !== $item;
            }
        );
    }

    private function getMethodName(MessageInterface $message): string
    {
        $methodNames = $this->getMethodNames($message);

        $this->throwExceptionIfNoProcessorFound($methodNames, $message);
        $this->throwExceptionIfMoreThanOneProcessorFound($methodNames, $message);

        return $methodNames[0];
    }
}
