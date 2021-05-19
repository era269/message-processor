<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Tests\MethodMap;

use DateTime;
use Era269\MessageProcessor\MethodMap\OneOrLessMethodMapDecorator;
use Era269\MethodMap\MethodMapInterface;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OneOrLessMethodMapDecoratorTest extends TestCase
{
    /**
     * @var MethodMapInterface|MockObject
     */
    private $map;
    /**
     * @var OneOrLessMethodMapDecorator
     */
    private $decorator;

    /**
     * @dataProvider methodNamesDataProvider
     *
     * @param string[] $expectedMethodNames
     */
    public function testGetMethodNames(array $expectedMethodNames): void
    {
        $this->map
            ->method('getMethodNames')
            ->willReturn($expectedMethodNames);

        self::assertEquals(
            $expectedMethodNames,
            $this->decorator->getMethodNames(new DateTime())
        );
    }

    public function testGetMethodNamesWithMoreThanOneMethod(): void
    {
        $expectedMethodNames = ['method-name-1', 'method-name-2'];
        $this->map
            ->method('getMethodNames')
            ->willReturn($expectedMethodNames);
        self::expectException(LogicException::class);
        $this->decorator->getMethodNames(new DateTime());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->map = $this->createMock(MethodMapInterface::class);
        $this->decorator = new OneOrLessMethodMapDecorator($this->map);
    }

    /**
     * @return array<int, array<int, string[]>>
     */
    public function methodNamesDataProvider(): array
    {
        return [
            [[]],
            [['method-name1']],
            [['method-name3']],
        ];
    }
}
