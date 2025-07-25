<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Test\Factory;

use GraphQL\Middleware\Contract\ErrorHandlerInterface;
use GraphQL\Middleware\Contract\RequestPreprocessorInterface;
use GraphQL\Middleware\Contract\ResponseFactoryInterface;
use GraphQL\Middleware\GraphQLMiddleware;
use GraphQL\Server\ServerConfig;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use WebonyxMiddleware\Factory\GraphQLMiddlewareFactory;

class GraphQLMiddlewareFactoryTest extends TestCase
{
    private GraphQLMiddlewareFactory $factory;
    private ContainerInterface $container;
    private ServerConfig $serverConfig;
    private ResponseFactoryInterface $responseFactory;
    private RequestPreprocessorInterface $requestPreprocessor;
    private ErrorHandlerInterface $errorHandler;

    protected function setUp(): void
    {
        $this->factory = new GraphQLMiddlewareFactory();
        $this->container = $this->createMock(ContainerInterface::class);
        $this->serverConfig = $this->createMock(ServerConfig::class);
        $this->responseFactory = $this->createMock(ResponseFactoryInterface::class);
        $this->requestPreprocessor = $this->createMock(RequestPreprocessorInterface::class);
        $this->errorHandler = $this->createMock(ErrorHandlerInterface::class);
    }

    public function testInvokeReturnsGraphQLMiddlewareWithMinimalConfig(): void
    {
        $config = [
            'graphql' => [
                'middleware' => [],
            ],
        ];

        $this->container
            ->expects($this->exactly(3))
            ->method('get')
            ->willReturnMap([
                ['config', $config],
                [ServerConfig::class, $this->serverConfig],
                [ResponseFactoryInterface::class, $this->responseFactory],
            ]);

        $this->container
            ->expects($this->exactly(2))
            ->method('has')
            ->willReturnMap([
                [RequestPreprocessorInterface::class, false],
                [ErrorHandlerInterface::class, false],
            ]);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(GraphQLMiddleware::class, $result);
    }

    public function testInvokeReturnsGraphQLMiddlewareWithFullConfig(): void
    {
        $config = [
            'graphql' => [
                'middleware' => [
                    'allowedHeaders' => ['application/json', 'application/graphql'],
                ],
            ],
        ];

        $this->container
            ->expects($this->exactly(5))
            ->method('get')
            ->willReturnMap([
                ['config', $config],
                [ServerConfig::class, $this->serverConfig],
                [ResponseFactoryInterface::class, $this->responseFactory],
                [RequestPreprocessorInterface::class, $this->requestPreprocessor],
                [ErrorHandlerInterface::class, $this->errorHandler],
            ]);

        $this->container
            ->expects($this->exactly(2))
            ->method('has')
            ->willReturnMap([
                [RequestPreprocessorInterface::class, true],
                [ErrorHandlerInterface::class, true],
            ]);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(GraphQLMiddleware::class, $result);
    }
}
