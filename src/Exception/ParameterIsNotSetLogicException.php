<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Exception;

use LogicException;

final class ParameterIsNotSetLogicException extends LogicException implements MessageProcessorExceptionInterface
{
    private const FORMAT_MESSAGE = '%s::%s is not set.';
    public function __construct(string $className, string $parameterName)
    {
        parent::__construct(sprintf(
            self::FORMAT_MESSAGE,
            $className,
            $parameterName
        ));
    }
}
