<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Factory;

use GraphQL\Middleware\Config\SchemaConfig;
use Psr\Container\ContainerInterface;

class SchemaConfigFactory
{
    private ContainerInterface $container;

    public function __invoke(ContainerInterface $container): SchemaConfig
    {
        $this->container = $container;
        $config = $container->get('config')['graphql']['schema'] ?? [];
        $typeConfigDecorator = $this->resolveTypeConfigDecorator($config['typeConfigDecorator']);
        $fieldConfigDecorator = $this->resolveFieldConfigDecorator($config['fieldConfigDecorator']);

        return new SchemaConfig(
            $config['schemaDirectories'],
            $config['isCacheEnabled'],
            $config['cacheDirectory'],
            $config['directoryChangeFilename'],
            $config['schemaFilename'],
            $config['parserOptions'],
            $config['resolverConfig'],
            $typeConfigDecorator,
            $config['schemaOptions'],
            $fieldConfigDecorator,
        );
    }

    private function resolveTypeConfigDecorator(mixed $typeConfigDecorator = null): ?callable
    {
        if ($typeConfigDecorator === null) {
            return null;
        }

        if ($this->container->has($typeConfigDecorator)) {
            $manager = $this->container->get($typeConfigDecorator);

            if (is_object($manager) && method_exists($manager, 'createTypeConfigDecorator')) {
                return $manager->createTypeConfigDecorator();
            }
        }

        if (is_callable($typeConfigDecorator)) {
            return $typeConfigDecorator;
        }

        return null;
    }

    private function resolveFieldConfigDecorator(mixed $fieldConfigDecorator = null): ?callable
    {
        if (!$fieldConfigDecorator) {
            return null;
        }

        if ($this->container->has($fieldConfigDecorator)) {
            $manager = $this->container->get($fieldConfigDecorator);

            if (is_object($manager) && method_exists($manager, 'createFieldConfigDecorator')) {
                return $manager->createFieldConfigDecorator();
            }
        }

        if (is_callable($fieldConfigDecorator)) {
            return $fieldConfigDecorator;
        }

        return null;
    }
}
