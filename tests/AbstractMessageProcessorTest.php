<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Tests;

use Era269\MessageProcessor\AbstractMessageProcessor;
use Era269\MessageProcessor\Message\EventInterface;
use Era269\MessageProcessor\Message\NullMessage;
use Era269\MessageProcessor\MessageInterface;
use Era269\MethodMap\MethodMapInterface;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\SimpleCache\CacheInterface;

class AbstractMessageProcessorTest extends TestCase
{
    /**
     * @var MessageInterface|MockObject
     */
    private $message;
    /**
     * @var AbstractMessageProcessor|MockObject
     */
    private $processor;

    public function testProcessWithNullMessageReturn(): void
    {
        $methodMap = $this->createMock(MethodMapInterface::class);
        $methodMap
            ->method('getMethodNames')
            ->willReturn(['processMessage']);

        $processor = new class extends AbstractMessageProcessor {
            /**
             * @return null
             */
            public function processMessage(MessageInterface $message)
            {
                return null;
            }
        };
        $processor->setMethodMap($methodMap);
        self::assertInstanceOf(NullMessage::class, $processor->process($this->message));
    }

    public function testProcessWithMessageReturn(): void
    {
        $methodMap = $this->createMock(MethodMapInterface::class);
        $methodMap
            ->method('getMethodNames')
            ->willReturn(['processMessage']);

        $processor = new class extends AbstractMessageProcessor {
            public function processMessage(MessageInterface $message): MessageInterface
            {
                return $message;
            }
        };
        $processor->setMethodMap($methodMap);
        self::assertInstanceOf(
            get_class($this->message),
            $processor->process($this->message)
        );
    }

    public function testProcess(): void
    {
        $processor = new class extends AbstractMessageProcessor {
            public function processMessage(MessageInterface $message): MessageInterface
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
        $methodMap = $this->createMock(MethodMapInterface::class);
        $methodMap
            ->method('getMethodNames')
            ->willReturn([]);
        $this->processor->setMethodMap($methodMap);
        $this->expectIncorrectInternalMethodCallException($this->message);
        $this->processor->process($this->message);
    }

    private function expectIncorrectInternalMethodCallException(MessageInterface $message): void
    {
        $this->expectExceptionMessage(sprintf(
            'Incorrect internal method call: object doesn\'t know how to process the message "%s"',
            get_class($message)
        ));
    }

    public function testProcessFailByTooManyMethods(): void
    {
        $methodMap = $this->createMock(MethodMapInterface::class);
        $methods = ['method1', 'method2'];
        $methodMap
            ->method('getMethodNames')
            ->willReturn($methods);
        $this->processor->setMethodMap($methodMap);
        $this->expectControversialInternalMethodCallException($this->message, $methods);
        $this->processor->process($this->message);
    }

    /**
     * @param string[] $methods
     */
    private function expectControversialInternalMethodCallException(MessageInterface $message, array $methods): void
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

    public function testSetMethodMap(): void
    {
        $this->processor->setMethodMap(
            $this->createMock(MethodMapInterface::class)
        );
        $this->expectException(LogicException::class);
        $this->processor->process($this->message);
    }

    public function testApplyMethod(): void
    {
        $event = new FakeEvent();
        $object = new class extends AbstractMessageProcessor {
            public function applyPublic(EventInterface $event): void
            {
                $this->apply($event);
            }

            protected function applyFakeEvent(FakeEvent $event): void
            {

            }
        };
        $object->setApplyEventMap(
            $this->createMock(MethodMapInterface::class)
        );
        $this->expectIncorrectInternalMethodCallException($event);

        $object->applyPublic($event);
    }

    public function testApplyFailNoApplyMethod(): void
    {
        $event = $this->createMock(EventInterface::class);
        $object = new class extends AbstractMessageProcessor {
            public function applyPublic(EventInterface $event): void
            {
                $this->apply($event);
            }
        };
        $this->expectIncorrectInternalMethodCallException($event);

        $object->applyPublic($event);
    }

    public function testApplyFailMoreThanOneApplyMethod(): void
    {
        $event = new FakeEvent();
        $object = new class extends AbstractMessageProcessor {
            public function applyPublic(EventInterface $event): void
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

        $object->applyPublic($event);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->message = $this->createMock(MessageInterface::class);
        $this->processor = $this->getMockForAbstractClass(AbstractMessageProcessor::class);
    }
}

class FakeEvent implements EventInterface
{
}
