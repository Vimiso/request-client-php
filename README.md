## Description

An easily extendable HTTP request client.

## Contents

* [Prerequisites](#prerequisites)
* [Installation](#installation)
* [Usage](#usage)

## Prerequisites

* PHP >= 7.0.0
* Composer

## Installation

```
composer require vimiso/request-client-php:1.*
```

## Usage

```php
<?php

use Vimiso\Http\Config;
use Vimiso\Http\Clients\JsonClient;
use Vimiso\Http\Exceptions\RequestException;

try {
    $config = new Config('https://foo.io');
    $client = new JsonClient($config);

    $response = $client->setMethod('get')
        ->setPath('/foo/bar')
        ->setHeaders(['foo' => 'bar'])
        ->setParams(['foo' => 'bar'])
        ->make();
} catch (RequestException $e) {
    $response = $e->getResponse();
    $statusCode = $e->getStatusCode();

    // Handle request exceptions as your wish...

    throw $e;
} catch (\Throwable $e) {
    // Handle all other exceptions as your wish...

    throw $e;
}
```
