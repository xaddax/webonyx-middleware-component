# Webonyx Middleware Component

[![Test](https://github.com/zestic/webonyx-middleware-component/workflows/Test/badge.svg)](https://github.com/zestic/webonyx-middleware-component/actions/workflows/test.yml)
[![codecov](https://codecov.io/gh/zestic/webonyx-middleware-component/branch/main/graph/badge.svg)](https://codecov.io/gh/zestic/webonyx-middleware-component)
[![License](https://img.shields.io/badge/License-Apache%202.0-blue.svg)](https://opensource.org/licenses/Apache-2.0)

This library provides Laminas framework integration for the [webonyx-psr15-middleware](https://github.com/xaddax/webonyx-psr15-middleware) package.

## Requirements

- PHP 8.3 or higher
- Composer

## Installation

```bash
composer require zestic/webonyx-middleware-component
```

## Usage

1. Register the module in your Laminas application:

```php
// config/modules.config.php
return [
    'WebonyxMiddleware',
    // ... other modules
];
```

2. Configure your GraphQL schema and middleware:

```php
// config/autoload/graphql.global.php
return [
    'graphql' => [
        'schema' => [
             // Look in webonyx-psr15-middleware for configuration
        ],
        'context' => [
            // Your context factory
        ],
        'debug' => true, // Enable in development
    ],
];
```

## Testing

Run the test suite:

```bash
composer test
```

Run static analysis:

```bash
composer stan
```

Run code style checks:

```bash
composer cs-check
```

Fix code style issues:

```bash
composer cs-fix
```

## License

This project is licensed under the Apache 2.0 License - see the LICENSE file for details. 