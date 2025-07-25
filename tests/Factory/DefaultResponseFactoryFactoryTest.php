<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Test\Factory;

use GraphQL\Middleware\Factory\DefaultResponseFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use WebonyxMiddleware\Factory\DefaultResponseFactoryFactory;

class DefaultResponseFactoryFactoryTest extends TestCase
{
    private DefaultResponseFactoryFactory $factory;
    private ContainerInterface $container;
    private ResponseFactoryInterface $responseFactory;
    private StreamFactoryInterface $streamFactory;

    protected function setUp(): void
    {
        $this->factory = new DefaultResponseFactoryFactory();
        $this->container = $this->createMock(ContainerInterface::class);
        $this->responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $this->streamFactory = $this->createMock(StreamFactoryInterface::class);
    }

    public function testInvokeReturnsDefaultResponseFactory(): void
    {
        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap([
                [ResponseFactoryInterface::class, $this->responseFactory],
                [StreamFactoryInterface::class, $this->streamFactory],
            ]);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(DefaultResponseFactory::class, $result);
    }
}
