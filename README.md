# Laravel 5 JSON API Server Package

*Compatible with Laravel 5.0, 5.1 & 5.2*

- A **JSON API Transformer** that will allow you to convert any mapped object into a valid JSON API resource.
- Controller boilerplate to write a fully compiliant **JSON API Server** using your **exisiting Eloquent Models**.
- Works for Laravel 5 and Lumen frameworks.

---

- [Installation](#installation)
- [Configuration (Laravel 5 & Lumen)](#configuration-laravel-5--lumen)
  - [Configuration for Laravel 5](#configuration-for-laravel-5)
        - [Step 1: Add the Service Provider](#step-1-add-the-service-provider)
        - [Step 2: Defining routes](#step-2-defining-routes)
        - [Step 3: Definition](#step-3-definition)
        - [Step 4: Usage](#step-4-usage)
  - [Configuration for Lumen](#configuration-for-lumen)
        - [Step 1: Add the Service Provider](#step-1-add-the-service-provider-1)
        - [Step 2: Defining routes](#step-2-defining-routes-1)
        - [Step 3: Definition](#step-3-definition-1)
        - [Step 4: Usage](#step-4-usage-1)
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
- [Common Errors and Solutions](#common-errors-and-solutions)

## Installation

Use [Composer](https://getcomposer.org) to install the package:

```
composer require carterzenk/slim3-jsonapi
```
