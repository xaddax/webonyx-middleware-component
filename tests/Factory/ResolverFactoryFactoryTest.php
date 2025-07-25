<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Test\Factory;

use GraphQL\Middleware\Factory\ResolverFactory;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use WebonyxMiddleware\Factory\ResolverFactoryFactory;

class ResolverFactoryFactoryTest extends TestCase
{
    private ResolverFactoryFactory $factory;
    private ContainerInterface $container;

    protected function setUp(): void
    {
        $this->factory = new ResolverFactoryFactory();
        $this->container = $this->createMock(ContainerInterface::class);
    }

    public function testInvokeReturnsResolverFactory(): void
    {
        $config = [
            'graphql' => [
                'generator' => [
                    'resolverConfig' => [
                        'namespace' => 'Application\\GraphQL\\Resolver',
                        'fileLocation' => '/src/Application/GraphQL/Resolver',
                        'templatePath' => '/templates/resolver.php.template',
                    ],
                ],
            ],
        ];

        $this->container
            ->expects($this->once())
            ->method('get')
            ->with('config')
            ->willReturn($config);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(ResolverFactory::class, $result);
    }
}
