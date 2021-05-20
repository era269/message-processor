<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Message;

use Era269\MessageProcessor\MessageInterface;

final class MessageCollection implements MessageCollectionInterface
{
    /**
     * @var MessageInterface[]
     */
    private $messages;
    /**
     * @var int
     */
    private $position = 0;

    public function __construct(
        MessageInterface ...$messages
    )
    {
        $this->messages = $messages;
    }

    public function current(): MessageInterface
    {
        return $this->messages[$this->position]
            ?? new NullMessage();
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->messages[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function attach(MessageInterface $message): void
    {
        $this->messages[] = $message;
    }

    public function count(): int
    {
        return count($this->messages);
    }
}
