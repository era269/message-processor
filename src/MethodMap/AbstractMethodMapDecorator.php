<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\MethodMap;

use Era269\MethodMap\MethodMapInterface;

abstract class AbstractMethodMapDecorator implements MethodMapInterface
{
    /**
     * @var MethodMapInterface
     */
    private $methodMap;

    public function __construct(MethodMapInterface $methodMap)
    {
        $this->methodMap = $methodMap;
    }

    /**
     * @inheritDoc
     */
    public function getMethodNames($object): array
    {
        return $this->getDecorated(
            $this->methodMap->getMethodNames($object),
            $object
        );
    }

    /**
     * @param string[] $methodNames
     *
     * @return string[]
     */
    abstract protected function getDecorated(array $methodNames, object $message): array;
}
