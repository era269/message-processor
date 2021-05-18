<?php

declare(strict_types=1);

namespace Era269\MessageProcessor;

use Era269\MessageProcessor\Message\NullMessage;
use Era269\MessageProcessor\Traits\CanGetMethodNameByMessageTrait;
use RuntimeException;

abstract class AbstractMessageProcessor implements MessageProcessorInterface
{
    use CanGetMethodNameByMessageTrait;

    /**
     * @throws RuntimeException
     */
    final public function process(MessageInterface $message): MessageInterface
    {
        $methodName = $this->getMethodName($message);

        return $this->$methodName($message)
            ?? new NullMessage();
    }
}
