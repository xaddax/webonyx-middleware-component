<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Test\Factory;

use GraphQL\Middleware\Factory\ResolverFactory;
use GraphQL\Middleware\Resolver\ResolverManager;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use WebonyxMiddleware\Factory\ResolverManagerFactory;

class ResolverManagerFactoryTest extends TestCase
{
    private ResolverManagerFactory $factory;
    private ContainerInterface $container;
    private ResolverFactory $resolverFactory;

    protected function setUp(): void
    {
        $this->factory = new ResolverManagerFactory();
        $this->container = $this->createMock(ContainerInterface::class);
        $this->resolverFactory = $this->createMock(ResolverFactory::class);
    }

    public function testInvokeReturnsResolverManager(): void
    {
        $fallbackResolver = function ($source, $args, $context, $info) {
            return $source[$info->fieldName] ?? null;
        };

        $config = [
            'graphql' => [
                'middleware' => [
                    'fallbackResolver' => $fallbackResolver,
                ],
            ],
        ];

        $this->container
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnMap([
                [ResolverFactory::class, $this->resolverFactory],
                ['config', $config],
            ]);

        $result = ($this->factory)($this->container);

        $this->assertInstanceOf(ResolverManager::class, $result);
    }
}
