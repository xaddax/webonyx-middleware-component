<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Factory;

use GraphQL\Middleware\GraphQLMiddleware;
use GraphQL\Middleware\Contract\RequestPreprocessorInterface;
use GraphQL\Middleware\Contract\ErrorHandlerInterface;
use GraphQL\Middleware\Contract\ResponseFactoryInterface;
use GraphQL\Server\ServerConfig;
use Psr\Container\ContainerInterface;

final class GraphQLMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): GraphQLMiddleware
    {
        $containerConfig = $container->get('config');
        $config = $containerConfig['graphql']['middleware'];

        return new GraphQLMiddleware(
            $container->get(ServerConfig::class),
            $container->get(ResponseFactoryInterface::class),
            $config['allowedHeaders'] ?? ['application/json'],
            $container->has(RequestPreprocessorInterface::class)
                ? $container->get(RequestPreprocessorInterface::class)
                : null,
            $container->has(ErrorHandlerInterface::class)
                ? $container->get(ErrorHandlerInterface::class)
                : null,
        );
    }
}
