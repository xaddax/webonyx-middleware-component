<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Factory;

use GraphQL\Middleware\Factory\ResolverFactory;
use GraphQL\Middleware\Resolver\ResolverManager;
use Psr\Container\ContainerInterface;

final class ResolverManagerFactory
{
    public function __invoke(ContainerInterface $container): ResolverManager
    {
        return new ResolverManager(
            $container->get(ResolverFactory::class),
            $container->get('config')['graphql']['middleware']['fallbackResolver'],
        );
    }
}
