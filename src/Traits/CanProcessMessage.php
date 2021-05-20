<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Traits;

use Era269\MessageProcessor\Message\MessageCollection;
use Era269\MessageProcessor\Message\MessageCollectionInterface;
use Era269\MessageProcessor\Message\NullMessage;
use Era269\MessageProcessor\MessageInterface;
use Era269\MessageProcessor\Traits\Aware\ProcessMessageMethodMapAwareTrait;
use RuntimeException;

trait CanProcessMessage
{
    use ProcessMessageMethodMapAwareTrait;

    /**
     * @throws RuntimeException
     */
    public function process(MessageInterface $message): MessageInterface
    {
        $messageCollection = $this->processMessage($message);

        switch ($messageCollection->count()) {
            case 0:
                return new NullMessage();
            case 1:
                return $messageCollection->current();
            default:
                return $messageCollection;
        }
    }

    private function processMessage(MessageInterface $message): MessageCollectionInterface
    {
        $methodNames = $this->getProcessMessageMethodMap()->getMethodNames($message);

        $messages = new MessageCollection();
        foreach ($methodNames as $methodName) {
            $messages->attach(
                $this->{$methodName}($message)
                ?? new NullMessage()
            );
        }

        return $messages;
    }
}
