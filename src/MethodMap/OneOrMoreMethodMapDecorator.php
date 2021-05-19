<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\MethodMap;

use Era269\MethodMap\MethodMapInterface;
use LogicException;

final class OneOrMoreMethodMapDecorator extends AbstractMethodMapDecorator implements MethodMapInterface
{
    /**
     * @inheritDoc
     */
    protected function getDecorated(array $methodNames, $message): array
    {
        if (empty($methodNames)) {
            throw new LogicException(sprintf(
                'Incorrect internal method call: object doesn\'t know how to process the message "%s"',
                get_class($message)
            ));
        }
        return $methodNames;
    }
}
