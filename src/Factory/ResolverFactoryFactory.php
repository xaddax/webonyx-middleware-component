<?php

declare(strict_types=1);

namespace Xaddax\WebonyxMiddleware\Factory;

use GraphQL\Middleware\Factory\ResolverFactory;
use Psr\Container\ContainerInterface;

class ResolverFactoryFactory
{
    public function __invoke(ContainerInterface $container): ResolverFactory
    {
        $containerConfig = $container->get('config');
        $config = $containerConfig['graphql']['resolver'];

        return new ResolverFactory($container, $config['namespace']);
    }
}
