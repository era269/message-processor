<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits;

use Era269\MessageProcessor\Message\NullMessage;
use Era269\MessageProcessor\MessageInterface;
use RuntimeException;

trait CanProcessMessage
{
    use CanGetMethodNameByMessageTrait;

    /**
     * @throws RuntimeException
     */
    public function process(MessageInterface $message): MessageInterface
    {
        $methodName = $this->getMethodName($message);

        return $this->{$methodName}($message)
            ?? new NullMessage();
    }
}
