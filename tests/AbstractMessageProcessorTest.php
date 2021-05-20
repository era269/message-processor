<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Tests;

use Era269\MessageProcessor\AbstractMessageProcessor;
use Era269\MessageProcessor\Message\NullMessage;
use Era269\MessageProcessor\MessageInterface;
use Era269\MethodMap\MethodMapInterface;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\SimpleCache\CacheInterface;

class AbstractMessageProcessorTest extends TestCase
{
    private const PROCESS_CORRECT_MESSAGE_METHOD_NAME        = 'processCorrectMessage';
    private const PROCESS_CONTROVERSIAL_MESSAGE_METHOD_NAMES = [
        'processControversialMessage1',
        'processControversialMessage2',
    ];
    /**
     * @var MessageInterface|MockObject
     */
    private $message;
    /**
     * @var AbstractMessageProcessor|MockObject
     */
    private $processor;
    /**
     * @var MockObject|EventDispatcherInterface
     */
    private $eventDispatcher;

    public function testProcessWithNullMessageReturn(): void
    {
        $methodMap = $this->createMock(MethodMapInterface::class);
        $methodMap
            ->method('getMethodNames')
            ->willReturn([self::PROCESS_CORRECT_MESSAGE_METHOD_NAME]);

        $processor = $this->createProcessor();
        $processor->setProcessMessageMethodMap($methodMap);
        self::assertInstanceOf(NullMessage::class, $processor->process(new FakeEvent()));
    }

    /**
     * @return AbstractMessageProcessor
     */
    private function createProcessor(): AbstractMessageProcessor
    {
        return new class ($this->eventDispatcher) extends AbstractMessageProcessor {
            public function processControversialMessage1(MessageInterface $message): void
            {
            }

            public function processControversialMessage2(MessageInterface $message): void
            {
            }

            public function processCorrectMessage(FakeEvent $message): void
            {
            }

            public function applyPublic(object $event): void
            {
                $this->apply($event);
            }

            protected function applyFakeEvent(FakeEvent $event): void
            {
            }
        };
    }

    public function testProcessWithMessageReturn(): void
    {
        $methodMap = $this->createMock(MethodMapInterface::class);
        $methodMap
            ->method('getMethodNames')
            ->willReturn(['processSomeMessage']);

        $processor = new class ($this->eventDispatcher) extends AbstractMessageProcessor {
            public function processSomeMessage(MessageInterface $message): MessageInterface
            {
                return $message;
            }
        };
        $processor->setProcessMessageMethodMap($methodMap);
        self::assertInstanceOf(
            get_class($this->message),
            $processor->process($this->message)
        );
    }

    public function testProcess(): void
    {
        $processor = new class ($this->eventDispatcher) extends AbstractMessageProcessor {
            public function processSomeMessage(MessageInterface $message): MessageInterface
            {
                return $message;
            }
        };
        self::assertInstanceOf(
            get_class($this->message),
            $processor->process($this->message)
        );
    }

    public function testProcessFailByNoProcessingMethod(): void
    {
        $this->expectIncorrectInternalMethodCallException($this->message);
        $this->processor->process($this->message);
    }

    private function expectIncorrectInternalMethodCallException(object $message): void
    {
        $this->expectExceptionMessage(sprintf(
            'Incorrect internal method call: object doesn\'t know how to process the message "%s"',
            get_class($message)
        ));
    }

    public function testProcessFailByTooManyMethods(): void
    {
        $this->expectControversialInternalMethodCallException(
            $this->message,
            self::PROCESS_CONTROVERSIAL_MESSAGE_METHOD_NAMES
        );
        $this->createProcessor()->process($this->message);
    }

    /**
     * @param string[] $methods
     */
    private function expectControversialInternalMethodCallException(object $message, array $methods): void
    {
        $this->expectExceptionMessage(sprintf(
            'Controversial internal method call. More than one method [%s] can process "%s"',
            implode(',', $methods),
            get_class($message)
        ));
    }

    public function testSetCache(): void
    {
        $this->processor->setCache(
            $this->createMock(CacheInterface::class)
        );
        $this->expectException(LogicException::class);
        $this->processor->process($this->message);
    }

    public function testNoCacheAndNoMethodMapProcessFail(): void
    {
        $this->expectException(LogicException::class);
        $this->processor->process($this->message);
    }

    public function testApplyMethod(): void
    {
        $event = new FakeEvent();
        $processor = $this->createProcessor();
        $applyEventMap = $this->createMock(MethodMapInterface::class);
        $applyEventMap
            ->expects($this->once())
            ->method('getMethodNames')
            ->willReturn(['applyFakeEvent']);
        $processor->setApplyEventMethodMap(
            $applyEventMap
        );
        $processor->applyPublic($event);
    }

    public function testApplyFailNoApplyMethod(): void
    {
        $event = new \stdClass();
        $processor = $this->createProcessor();
        $this->expectIncorrectInternalMethodCallException($event);

        $processor->applyPublic($event);
    }

    public function testApplyFailMoreThanOneApplyMethod(): void
    {
        $event = new FakeEvent();
        $processor = new class ($this->eventDispatcher) extends AbstractMessageProcessor {
            public function applyPublic(MessageInterface $event): void
            {
                $this->apply($event);
            }

            protected function applyEvent1(FakeEvent $event)
            {
            }

            protected function applyEvent2(FakeEvent $event)
            {
            }
        };

        $this->expectControversialInternalMethodCallException($event, ['applyEvent1', 'applyEvent2']);

        $processor->applyPublic($event);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->message = $this->createMock(MessageInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->processor = $this->getMockForAbstractClass(AbstractMessageProcessor::class, [$this->eventDispatcher]);
    }
}

class FakeEvent implements MessageInterface
{
}
