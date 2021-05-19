<?php

declare(strict_types=1);

namespace Era269\MessageProcessor\Tests\MethodMap;

use DateTime;
use Era269\MessageProcessor\MethodMap\ExcludeMethodMapDecorator;
use Era269\MethodMap\MethodMapInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ExcludeMethodMapDecoratorTest extends TestCase
{
    private const X = ['x'];
    private const XY = ['x', 'y'];
    private const EMPTY = [];
    private const NOT_PRESENT = ['not-present'];
    /**
     * @var MethodMapInterface|MockObject
     */
    private $map;

    /**
     * @dataProvider methodNamesDataProvider
     *
     * @param string[] $inMethodNames
     * @param string[] $expectedMethodNames
     * @param string[] $exclude
     */
    public function testGetMethodNames(array $inMethodNames, array $expectedMethodNames, array $exclude): void
    {
        $this->map
            ->method('getMethodNames')
            ->willReturn($inMethodNames);

        $decorator = new ExcludeMethodMapDecorator($this->map, $exclude);
        self::assertEquals(
            $expectedMethodNames,
            $decorator->getMethodNames(new DateTime())
        );
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->map = $this->createMock(MethodMapInterface::class);
    }

    /**
     * @return array<int, array<string, string[]>>
     */
    public function methodNamesDataProvider(): array
    {
        return [
            ['in' => self::X, 'expected' => self::X, 'exclude' => self::EMPTY],
            ['in' => self::X, 'expected' => self::EMPTY, 'exclude' => self::X],
            ['in' => self::X, 'expected' => self::X, 'exclude' => self::NOT_PRESENT],
            ['in' => self::XY, 'expected' => self::XY, 'exclude' => self::NOT_PRESENT],
            ['in' => self::XY, 'expected' => self::EMPTY, 'exclude' => self::XY],
        ];
    }
}
