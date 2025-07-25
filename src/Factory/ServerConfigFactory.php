<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Factory;

use GraphQL\Server\ServerConfig;
use GraphQL\Type\Schema;
use Psr\Container\ContainerInterface;

final class ServerConfigFactory
{
    public function __invoke(ContainerInterface $container): ServerConfig
    {
        $containerConfig = $container->get('config');

        // the schema is wrong here. it should be a SchemaConfig instance,
        // right now its GeneratedSchemaFactory
        $config = $containerConfig['graphql']['server'] ?? [];

        $schema = $config['schema'] ?? Schema::class;
        $config['schema'] = $container->get($schema);

        $callables = [
            'context',
            'errorFormatter',
            'errorsHandler',
            'fieldResolver',
            'persistentQueryLoader',
            'rootValue',
        ];
        foreach ($callables as $callableProperty) {
            if (isset($config[$callableProperty])) {
                $callable = $config[$callableProperty];
                if (!is_callable($config[$callableProperty])) {
                    $callable = $container->get($callable);
                }
                $config[$callableProperty] = $callable;
            }
        }

        return ServerConfig::create($config);
    }
}
