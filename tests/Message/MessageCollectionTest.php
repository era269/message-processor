<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Tests\Message;

use Era269\MessageProcessor\Message\MessageCollection;
use Era269\MessageProcessor\MessageInterface;
use PHPUnit\Framework\TestCase;

class MessageCollectionTest extends TestCase
{
    /**
     * @var MessageCollection
     */
    private $messageCollection;
    /**
     * @var MessageInterface[]
     */
    private $messages;

    public function test(): void
    {
        foreach ($this->messages as $message) {
            $this->messageCollection->attach($message);
        }
        foreach ($this->messageCollection as $key => $item) {
            self::assertInstanceOf(MessageInterface::class, $item);
        }
        self::assertEquals(count($this->messages) + 1, count($this->messageCollection));
    }

    public function testCurrentOnEmpty(): void
    {
        self::assertInstanceOf(
            MessageInterface::class,
            (new MessageCollection())->current()
        );
    }

    protected function setUp(): void
    {
        parent::setUp();

        $message = $this->createMock(MessageInterface::class);
        $this->messageCollection = new MessageCollection(
            $message
        );
        $this->messages = [
            clone $message,
            clone $message,
            clone $message,
        ];
    }
}
