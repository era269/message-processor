<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Message;

use Countable;
use Era269\MessageProcessor\MessageInterface;
use Iterator;

/**
 * @extends Iterator<int, MessageInterface>
 */
interface MessageCollectionInterface extends Iterator, Countable, MessageInterface
{
    public function current(): MessageInterface;

    public function next(): void;

    public function key(): int;

    public function valid(): bool;

    public function rewind(): void;

    public function attach(MessageInterface $event): void;
}
