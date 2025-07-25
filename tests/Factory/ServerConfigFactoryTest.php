<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Test\Factory;

use GraphQL\Server\ServerConfig;
use GraphQL\Type\Schema;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use WebonyxMiddleware\Factory\ServerConfigFactory;

class ServerConfigFactoryTest extends TestCase
{
    private ServerConfigFactory $factory;
    private ContainerInterface $container;
    private Schema $schema;

    protected function setUp(): void
    {
        $this->factory = new ServerConfigFactory();
        $this->container = $this->createMock(ContainerInterface::class);
        $this->schema = $this->createMock(Schema::class);
    }

    public function testInvokeReturnsServerConfigWithMinimalConfig(): void
    {
        $config = [
            'graphql' => [],
        ];

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap([
                ['config', $config],
                [Schema::class, $this->schema],
            ]);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(ServerConfig::class, $result);
    }

    public function testInvokeReturnsServerConfigWithCustomSchema(): void
    {
        $customSchemaClass = 'CustomSchema';
        $customSchema = $this->createMock(Schema::class);

        $config = [
            'graphql' => [
                'server' => [
                    'schema' => $customSchemaClass,
                ],
            ],
        ];

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap([
                ['config', $config],
                [$customSchemaClass, $customSchema],
            ]);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(ServerConfig::class, $result);
    }

    public function testInvokeReturnsServerConfigWithCallableProperties(): void
    {
        $contextCallable = function () {
            return 'context';
        };
        $errorFormatterCallable = function () {
            return 'error_formatter';
        };

        $config = [
            'graphql' => [
                'server' => [
                    'context' => $contextCallable,
                    'errorFormatter' => $errorFormatterCallable,
                    'errorsHandler' => $contextCallable,
                    'fieldResolver' => $errorFormatterCallable,
                    'persistedQueryLoader' => $contextCallable,
                    'rootValue' => $errorFormatterCallable,
                ],
            ],
        ];

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap([
                ['config', $config],
                [Schema::class, $this->schema],
            ]);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(ServerConfig::class, $result);
    }

    public function testInvokeReturnsServerConfigWithServiceCallables(): void
    {
        $contextService = 'ContextService';
        $errorFormatterService = 'ErrorFormatterService';
        $contextCallable = function () {
            return 'context';
        };
        $errorFormatterCallable = function () {
            return 'error_formatter';
        };

        $config = [
            'graphql' => [
                'server' => [
                    'context' => $contextService,
                    'errorFormatter' => $errorFormatterService,
                    'errorsHandler' => $contextService,
                    'fieldResolver' => $errorFormatterService,
                    'persistedQueryLoader' => $contextCallable,
                    'rootValue' => $errorFormatterService,
                ],
            ],
        ];

        $this->container
            ->expects($this->exactly(7))
            ->method('get')
            ->willReturnMap([
                ['config', $config],
                [Schema::class, $this->schema],
                [$contextService, $contextCallable],
                [$errorFormatterService, $errorFormatterCallable],
                [$contextService, $contextCallable],
                [$errorFormatterService, $errorFormatterCallable],
                [$errorFormatterService, $errorFormatterCallable],
            ]);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(ServerConfig::class, $result);
    }
}
