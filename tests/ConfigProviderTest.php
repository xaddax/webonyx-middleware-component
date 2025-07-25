<?php

declare(strict_types=1);

namespace WebonyxMiddleware\Test;

use PHPUnit\Framework\TestCase;
use WebonyxMiddleware\ConfigProvider;

class ConfigProviderTest extends TestCase
{
    private ConfigProvider $configProvider;

    protected function setUp(): void
    {
        $this->configProvider = new ConfigProvider();
    }

    public function testInvokeReturnsExpectedStructure(): void
    {
        $config = ($this->configProvider)();

        $this->assertArrayHasKey('dependencies', $config);
        $this->assertArrayHasKey('graphql', $config);
        $this->assertArrayHasKey('factories', $config['dependencies']);
        $this->assertArrayHasKey('schema', $config['graphql']);
    }

    public function testGetDependenciesReturnsExpectedStructure(): void
    {
        $dependencies = $this->configProvider->getDependencies();

        $this->assertArrayHasKey('factories', $dependencies);
    }

    public function testGetGraphQLConfigReturnsExpectedStructure(): void
    {
        $graphqlConfig = $this->configProvider->getGraphQLConfig();

        $this->assertArrayHasKey('generator', $graphqlConfig);
        $this->assertArrayHasKey('middleware', $graphqlConfig);
        $this->assertArrayHasKey('schema', $graphqlConfig);
        $this->assertArrayHasKey('server', $graphqlConfig);

        // Test server config structure
        $this->assertArrayHasKey('context', $graphqlConfig['server']);

        // Test generator config structure
        $this->assertArrayHasKey('entityConfig', $graphqlConfig['generator']);
        $this->assertArrayHasKey('requestConfig', $graphqlConfig['generator']);
        $this->assertArrayHasKey('resolverConfig', $graphqlConfig['generator']);
        $this->assertArrayHasKey('typeMappings', $graphqlConfig['generator']);
        $this->assertArrayHasKey('customTypes', $graphqlConfig['generator']);
        $this->assertArrayHasKey('isImmutable', $graphqlConfig['generator']);
        $this->assertArrayHasKey('hasStrictTypes', $graphqlConfig['generator']);

        // Test middleware config structure
        $this->assertArrayHasKey('fallbackResolver', $graphqlConfig['middleware']);
        $this->assertIsCallable($graphqlConfig['middleware']['fallbackResolver']);

        // Test schema config structure
        $this->assertArrayHasKey('schemaDirectories', $graphqlConfig['schema']);
        $this->assertArrayHasKey('isCacheEnabled', $graphqlConfig['schema']);
        $this->assertArrayHasKey('cacheDirectory', $graphqlConfig['schema']);
        $this->assertArrayHasKey('directoryChangeFilename', $graphqlConfig['schema']);
        $this->assertArrayHasKey('schemaFilename', $graphqlConfig['schema']);
        $this->assertArrayHasKey('parserOptions', $graphqlConfig['schema']);
        $this->assertArrayHasKey('resolverConfig', $graphqlConfig['schema']);
        $this->assertArrayHasKey('typeConfigDecorator', $graphqlConfig['schema']);
        $this->assertArrayHasKey('schemaOptions', $graphqlConfig['schema']);
        $this->assertArrayHasKey('fieldConfigDecorator', $graphqlConfig['schema']);
    }

    public function testGetGraphQLConfigReturnsValidPaths(): void
    {
        $graphqlConfig = $this->configProvider->getGraphQLConfig();

        // Test that the paths are properly constructed
        $this->assertIsString($graphqlConfig['generator']['entityConfig']['fileLocation']);
        $this->assertIsString($graphqlConfig['generator']['requestConfig']['fileLocation']);
        $this->assertIsString($graphqlConfig['generator']['resolverConfig']['fileLocation']);

        // Test that template paths are constructed (even if they don't exist)
        $this->assertIsString($graphqlConfig['generator']['entityConfig']['templatePath']);
        $this->assertIsString($graphqlConfig['generator']['requestConfig']['templatePath']);
        $this->assertIsString($graphqlConfig['generator']['resolverConfig']['templatePath']);
    }
}
