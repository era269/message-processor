<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\MethodMap;

use Era269\MethodMap\MethodMapInterface;

final class ExcludeMethodMapDecorator extends AbstractMethodMapDecorator implements MethodMapInterface
{
    /**
     * @var string[]
     */
    private $exclude;

    /**
     * @param string[] $exclude
     */
    public function __construct(MethodMapInterface $methodMap, array $exclude)
    {
        parent::__construct($methodMap);
        $this->exclude = $exclude;
    }

    /**
     * @inheritDoc
     */
    protected function getDecorated(array $methodNames, $message): array
    {
        return array_diff(
            $methodNames,
            $this->exclude
        );
    }
}
