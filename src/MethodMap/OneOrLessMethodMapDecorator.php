<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\MethodMap;

use Era269\MethodMap\MethodMapInterface;
use LogicException;

final class OneOrLessMethodMapDecorator extends AbstractMethodMapDecorator implements MethodMapInterface
{
    /**
     * @inheritDoc
     */
    protected function getDecorated(array $methodNames, $message): array
    {
        if (count($methodNames) > 1) {
            throw new LogicException(sprintf(
                'Controversial internal method call. More than one method [%s] can process "%s"',
                implode(',', $methodNames),
                get_class($message)
            ));
        }
        return $methodNames;
    }
}
