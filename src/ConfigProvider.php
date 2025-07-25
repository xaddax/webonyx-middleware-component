<?php

declare(strict_types=1);

namespace WebonyxMiddleware;

use GraphQL\Middleware\GraphQLMiddleware;
use GraphQL\Middleware\Config\GeneratorConfig;
use GraphQL\Middleware\Config\SchemaConfig;
use GraphQL\Middleware\Contract\ErrorHandlerInterface;
use GraphQL\Middleware\Contract\ResponseFactoryInterface;
use GraphQL\Middleware\Contract\TemplateEngineInterface;
use GraphQL\Middleware\Contract\TypeMapperInterface;
use GraphQL\Middleware\Context\RequestContext;
use GraphQL\Middleware\Error\DefaultErrorHandler;
use GraphQL\Middleware\Factory\GeneratedSchemaFactory;
use GraphQL\Middleware\Factory\ResolverFactory;
use GraphQL\Middleware\Generator\DefaultTypeMapper;
use GraphQL\Middleware\Generator\SimpleTemplateEngine;
use GraphQL\Middleware\Resolver\ResolverManager;
use GraphQL\Server\ServerConfig;
use GraphQL\Type\Schema;
use WebonyxMiddleware\Factory\DefaultResponseFactoryFactory;
use WebonyxMiddleware\Factory\GeneratorConfigFactory;
use WebonyxMiddleware\Factory\GraphQLMiddlewareFactory;
use WebonyxMiddleware\Factory\ResolverFactoryFactory;
use WebonyxMiddleware\Factory\ResolverManagerFactory;
use WebonyxMiddleware\Factory\SchemaConfigFactory;
use WebonyxMiddleware\Factory\ServerConfigFactory;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'graphql' => $this->getGraphQLConfig(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'aliases' => [
                ErrorHandlerInterface::class => DefaultErrorHandler::class,
                TemplateEngineInterface::class => SimpleTemplateEngine::class,
                TypeMapperInterface::class => DefaultTypeMapper::class,
            ],
            'factories' => [
                GraphQLMiddleware::class => GraphQLMiddlewareFactory::class,
                GeneratorConfig::class => GeneratorConfigFactory::class,
                ResolverFactory::class => ResolverFactoryFactory::class,
                ResolverManager::class => ResolverManagerFactory::class,
                ResponseFactoryInterface::class => DefaultResponseFactoryFactory::class,
                SchemaConfig::class => SchemaConfigFactory::class,
                ServerConfig::class => ServerConfigFactory::class,
                Schema::class => GeneratedSchemaFactory::class,
            ],
        ];
    }

    public function getGraphQLConfig(): array
    {
        $srcDir = getcwd() . '/src';
        $templatesDir = __DIR__ . '/../../webonyx-psr15-middleware/templates';
        $templatesDir = realpath($templatesDir);

        return [
            'generator' => [
                'entityConfig' => [
                    'namespace' => 'Domain\\Entity',
                    'fileLocation' => $srcDir . '/Domain/Entity',
                    'templatePath' => $templatesDir . '/entity.php.template',
                ],
                'requestConfig' => [
                    'namespace' => 'Application\\GraphQL\\Request',
                    'fileLocation' => $srcDir . '/Application/GraphQL/Request',
                    'templatePath' => $templatesDir . '/request.php.template',
                ],
                'resolverConfig' => [
                    'namespace' => 'Application\\GraphQL\\Resolver',
                    'fileLocation' => $srcDir . '/Application/GraphQL/Resolver',
                    'templatePath' => $templatesDir . '/resolver.php.template',
                ],
                'typeMappings' => [],
                'customTypes' => [],
                'isImmutable' => false,
                'hasStrictTypes' => false,
            ],
            'middleware' => [
                'fallbackResolver' => function ($source, $args, $context, $info) {
                    return $source[$info->fieldName] ?? null;
                },
            ],
            'schema' => [
                'schemaDirectories' => [],
                'isCacheEnabled' => false,
                'cacheDirectory' => sys_get_temp_dir(),
                'directoryChangeFilename' => 'graphql-directory-changes.php',
                'schemaFilename' => 'graphql-schema.php',
                'parserOptions' => [],
                'resolverConfig' => [],
                'typeConfigDecorator' => ResolverManager::class,
                'schemaOptions' => [],
                'fieldConfigDecorator' => ResolverManager::class,
            ],
            'context' => RequestContext::class,
            'root_value' => null,
            'field_resolver' => null,
            'validation_rules' => [],
            'error_formatter' => null,
            'debug' => false,
            'server' => [
                'context' => RequestContext::class,
            ],
        ];
    }
}
