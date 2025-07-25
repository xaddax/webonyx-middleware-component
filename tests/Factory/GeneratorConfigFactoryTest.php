<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Test\Factory;

use GraphQL\Middleware\Config\GeneratorConfig;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use WebonyxMiddleware\Factory\GeneratorConfigFactory;

class GeneratorConfigFactoryTest extends TestCase
{
    private GeneratorConfigFactory $factory;
    private ContainerInterface $container;

    protected function setUp(): void
    {
        $this->factory = new GeneratorConfigFactory();
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testInvokeReturnsGeneratorConfig(): void
    {
        $config = [
            'graphql' => [
                'generator' => [
                    'entityConfig' => [
                        'namespace' => 'Domain\\Entity',
                        'fileLocation' => '/src/Domain/Entity',
                        'templatePath' => '/templates/entity.php.template',
                    ],
                    'requestConfig' => [
                        'namespace' => 'Application\\GraphQL\\Request',
                        'fileLocation' => '/src/Application/GraphQL/Request',
                        'templatePath' => '/templates/request.php.template',
                    ],
                    'resolverConfig' => [
                        'namespace' => 'Application\\GraphQL\\Resolver',
                        'fileLocation' => '/src/Application/GraphQL/Resolver',
                        'templatePath' => '/templates/resolver.php.template',
                    ],
                    'typeMappings' => [],
                    'customTypes' => [],
                    'isImmutable' => false,
                    'hasStrictTypes' => false,
                ],
            ],
        ];

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(GeneratorConfig::class, $result);
    }
}
