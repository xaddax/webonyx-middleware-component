<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Factory;

use GraphQL\Middleware\Config\GeneratorConfig;
use Psr\Container\ContainerInterface;

class GeneratorConfigFactory
{
    public function __invoke(ContainerInterface $container): GeneratorConfig
    {
        $config = $container->get('config')['graphql']['generator'];

        return new GeneratorConfig($config);
    }
}
