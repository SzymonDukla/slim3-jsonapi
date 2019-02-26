# Slim 3 Eloquent JSON API Server Package

*Compatible with Slim Framework 3.0, 3.1*

- A **JSON API Transformer** that will allow you to convert any mapped object into a valid JSON API resource.
- Controller boilerplate to write a fully compiliant **JSON API Server** using your **exisiting Eloquent Models**.
- Works with Slim Framework 3 and Eloquent 5.

---

- [Installation](#installation)
- [Configuration for Slim 3 and Eloquent](#configuration-for-slim-3)
    - [Step 1: Define Mappings](#step-1-define-mappings)
    - [Step 2: Define Dependency](#step-2-define-dependency)
    - [Step 4: Usage](#step-4-usage)
- [JsonApiController](#jsonapicontroller)
- [Examples: Consuming the API](#examples-consuming-the-api)
  - [GET](#get)
  - [POST](#post)
  - [PUT](#put)
  - [PATCH](#patch)
  - [DELETE](#delete)
- [GET Query Params: include, fields, sort and page](#get-query-params-include-fields-sort-and-page)
- [POST/PUT/PATCH with Relationships](#postputpatch-with-relationships)
- [Custom Response Headers](#custom-response-headers)

## Installation

Use [Composer](https://getcomposer.org) to install the package:

```
composer require szymondukla/slim3-jsonapi
```

## Configuration for Slim 3 and Eloquent

### Step 1: Define Mappings

Open up your settings file and add an entry for your mappings:

```php
return [
    'settings' => [

        // JSON-API settings
        'json-api' => [
            'mappings' => [
                Mappings\ContactMapping::class,
                Mappings\OwnerMapping::class
            ]
        ]

    ]
];
```

### Step 2: Define Dependency

Open up your dependencies file and add an entry for the JsonApiSerializer, using the mappings you defined in the settings.

```php
$container[SzymonDukla\Slim3\JsonApi\JsonApiSerializer::class] = function (ContainerInterface $c) {
    $mappings = $c->get('settings')['json-api']['mappings'];

    $mapper = new SzymonDukla\Slim3\JsonApi\Mapper\Mapper($mappings);

    $mappingHelper = new SzymonDukla\Slim3\JsonApi\Mapper\MappingHelper();
    $parsedRoutes = $mappingHelper->parseRoutes($mapper);

    $transformer = new NilPortugues\Api\JsonApi\JsonApiTransformer($parsedRoutes);

    return new SzymonDukla\Slim3\JsonApi\JsonApiSerializer($transformer);
};
```