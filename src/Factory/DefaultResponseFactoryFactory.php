<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Factory;

use GraphQL\Middleware\Factory\DefaultResponseFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface as PsrResponseFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

class DefaultResponseFactoryFactory
{
    public function __invoke(ContainerInterface $container): DefaultResponseFactory
    {
        return new DefaultResponseFactory(
            $container->get(PsrResponseFactoryInterface::class),
            $container->get(StreamFactoryInterface::class),
        );
    }
}
