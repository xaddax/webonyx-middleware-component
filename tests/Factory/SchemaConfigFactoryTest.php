<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Test\Factory;

use GraphQL\Middleware\Config\SchemaConfig;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use WebonyxMiddleware\Factory\SchemaConfigFactory;

class SchemaConfigFactoryTest extends TestCase
{
    private SchemaConfigFactory $factory;
    private ContainerInterface $container;

    protected function setUp(): void
    {
        $this->factory = new SchemaConfigFactory();
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testInvokeReturnsSchemaConfigWithMinimalConfig(): void
    {
        $config = [
            'graphql' => [
                'schema' => [
                    'schemaDirectories' => ['/path/to/schema'],
                    'isCacheEnabled' => false,
                    'cacheDirectory' => '/tmp',
                    'directoryChangeFilename' => 'changes.php',
                    'schemaFilename' => 'schema.php',
                    'parserOptions' => [],
                    'resolverConfig' => [],
                    'typeConfigDecorator' => null,
                    'schemaOptions' => [],
                    'fieldConfigDecorator' => null,
                ],
            ],
        ];

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(SchemaConfig::class, $result);
    }

    public function testInvokeReturnsSchemaConfigWithServiceDecorators(): void
    {
        $typeDecoratorService = 'TypeDecoratorService';
        $fieldDecoratorService = 'FieldDecoratorService';

        $config = [
            'graphql' => [
                'schema' => [
                    'schemaDirectories' => ['/path/to/schema'],
                    'isCacheEnabled' => true,
                    'cacheDirectory' => '/cache',
                    'directoryChangeFilename' => 'changes.php',
                    'schemaFilename' => 'schema.php',
                    'parserOptions' => ['option' => 'value'],
                    'resolverConfig' => ['resolver' => 'config'],
                    'typeConfigDecorator' => $typeDecoratorService,
                    'schemaOptions' => ['schema' => 'option'],
                    'fieldConfigDecorator' => $fieldDecoratorService,
                ],
            ],
        ];

        $typeCallable = function () {
            return 'type_decorator';
        };
        $fieldCallable = function () {
            return 'field_decorator';
        };

        // Mock service that has the required method
        $typeDecoratorMock = new class {
            public function createTypeConfigDecorator(): callable
            {
                return function () {
                    return 'type_decorator';
                };
            }
        };

        $fieldDecoratorMock = new class {
            public function createFieldConfigDecorator(): callable
            {
                return function () {
                    return 'field_decorator';
                };
            }
        };

        $this->container
            ->expects($this->exactly(3))
            ->method('get')
            ->willReturnMap([
                ['config', $config],
                [$typeDecoratorService, $typeDecoratorMock],
                [$fieldDecoratorService, $fieldDecoratorMock],
            ]);

        $this->container
            ->expects($this->exactly(2))
            ->method('has')
            ->willReturnMap([
                [$typeDecoratorService, true],
                [$fieldDecoratorService, true],
            ]);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(SchemaConfig::class, $result);
    }



    public function testInvokeReturnsSchemaConfigWithNonExistentServices(): void
    {
        $config = [
            'graphql' => [
                'schema' => [
                    'schemaDirectories' => ['/path/to/schema'],
                    'isCacheEnabled' => false,
                    'cacheDirectory' => '/tmp',
                    'directoryChangeFilename' => 'changes.php',
                    'schemaFilename' => 'schema.php',
                    'parserOptions' => [],
                    'resolverConfig' => [],
                    'typeConfigDecorator' => 'NonExistentService',
                    'schemaOptions' => [],
                    'fieldConfigDecorator' => 'AnotherNonExistentService',
                ],
            ],
        ];

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $this->container
            ->expects($this->exactly(2))
            ->method('has')
            ->willReturnMap([
                ['NonExistentService', false],
                ['AnotherNonExistentService', false],
            ]);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(SchemaConfig::class, $result);
    }

    public function testInvokeReturnsSchemaConfigWithCallableDecorators(): void
    {
        $typeCallable = function () {
            return 'type_decorator';
        };
        $fieldCallable = function () {
            return 'field_decorator';
        };

        $config = [
            'graphql' => [
                'schema' => [
                    'schemaDirectories' => ['/path/to/schema'],
                    'isCacheEnabled' => false,
                    'cacheDirectory' => '/tmp',
                    'directoryChangeFilename' => 'changes.php',
                    'schemaFilename' => 'schema.php',
                    'parserOptions' => [],
                    'resolverConfig' => [],
                    'typeConfigDecorator' => 'NonExistentCallableService',
                    'schemaOptions' => [],
                    'fieldConfigDecorator' => 'AnotherNonExistentCallableService',
                ],
            ],
        ];

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $this->container
            ->expects($this->exactly(2))
            ->method('has')
            ->willReturnMap([
                ['NonExistentCallableService', false],
                ['AnotherNonExistentCallableService', false],
            ]);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(SchemaConfig::class, $result);
    }



    public function testInvokeReturnsSchemaConfigWithEmptyFieldDecorator(): void
    {
        $config = [
            'graphql' => [
                'schema' => [
                    'schemaDirectories' => ['/path/to/schema'],
                    'isCacheEnabled' => false,
                    'cacheDirectory' => '/tmp',
                    'directoryChangeFilename' => 'changes.php',
                    'schemaFilename' => 'schema.php',
                    'parserOptions' => [],
                    'resolverConfig' => [],
                    'typeConfigDecorator' => null,
                    'schemaOptions' => [],
                    'fieldConfigDecorator' => '', // Empty string to test the !$fieldConfigDecorator condition
                ],
            ],
        ];

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(SchemaConfig::class, $result);
    }
}
