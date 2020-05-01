
# RestApi plugin for CakePHP 3

[![Build Status](https://travis-ci.org/multidots/cakephp-rest-api.svg?branch=master)](https://travis-ci.org/multidots/cakephp-rest-api)

This plugin provides basic support for building REST API services in your CakePHP 3 application.

## Requirements

This plugin has the following requirements:

* CakePHP 3.0.0 or greater.
* PHP 5.4.16 or greater.

## Installation

You can install this plugin into your CakePHP application using [composer](http://getcomposer.org).

The recommended way to install composer packages is:

```
composer require multidots/cakephp-rest-api
```

After installation, [Load the plugin](http://book.cakephp.org/3.0/en/plugins.html#loading-a-plugin)

```php
Plugin::load('RestApi', ['bootstrap' => true]);
```
Or, you can load the plugin using the shell command
```sh
$ bin/cake plugin load -b RestApi
```
## Usage

You just need to create your API related controller and extend it to `ApiController` instead of default `AppController`.  You just need to set you results in `apiResponse` variable and your response code in `httpStatusCode` variable. For example,

```php
namespace App\Controller;

use RestApi\Controller\ApiController;

/**
 * Foo Controller
 */
class FooController extends ApiController
{

    /**
     * bar method
     *
     */
    public function bar()
    {
		// your action logic

		// Set the HTTP status code. By default, it is set to 200
		$this->httpStatusCode = 200;

		// Set the response
		$this->apiResponse['you_response'] = 'your response data';
    }
}
```

You can define your logic in your action function as per your need. For above example, you will get following response in `json` format,

```json
{"status":"OK","result":{"you_response":"your response data"}}
```

The URL for above example will be `http://yourdomain.com/foo/bar`. You can customize it by setting the routes in `APP/config/routes.php`.

Simple :)

## Configurations

### cors
As of now, this plugin provides configuration for CORS requests. By default, cors requests are enabled and allowed from all domains. You can overwrite these settings by creating config file at `APP/config/api.php` . The content of file will look like,

```php
<?php
return [
    'ApiRequest' => [
        'cors' => [
            'enabled' => true,
            'origin' => '*'
        ]
    ]
];
```

To disable cors request, set `enabled` flag to `false`. To allow requests from specific domains, set them in `origin` option like,

```php
<?php
return [
    'ApiRequest' => [
        'cors' => [
            'enabled' => true,
            'origin' => ['localhost', 'www.example.com', '*.example.com']
        ]
    ]
];
```

## Response format
The default response format of API is `json` and its structure is defined as below.

```json
{
  "status": "OK",
  "result": {
    //your result data
  }
}
```

If you have set httpResponseCode to any value other that 200, the `status` value will be `NOK` otherwise `OK`. In case of exceptions, it will be handled automatically and set the appropriate status code.

## Examples
Below are few examples to understand how this plugin works.

### Retrieve articles

Let's create an API which returns a list of articles with basic details like id and title. Our controller will look like,

```php
<?php

namespace App\Controller;

use RestApi\Controller\ApiController;

/**
 * Articles Controller
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 */
class ArticlesController extends ApiController
{

    /**
     * index method
     *
     */
    public function index()
    {
        $articles = $this->Articles->find('all')
            ->select(['id', 'title'])
            ->toArray();

        $this->apiResponse['articles'] = $articles;
    }
}
```
The response of above API call will look like,
```json
{
  "status": "OK",
  "result": {
    "articles": [
      {
        "id": 1,
        "title": "Lorem ipsum"
      },
      {
        "id": 2,
        "title": "Donec hendrerit"
      }
    ]
  }
}
```

### Exception handling

This plugin will handle the exceptions being thrown from your action. For example, if you API method only allows `POST` method and someone makes a `GET` request, it will generate `NOK` response with proper HTTP response code. For example, 

```php
<?php

namespace App\Controller;

use RestApi\Controller\ApiController;

/**
 * Foo Controller
 *
 */
class FooController extends ApiController
{

    /**
     * bar method
     *
     */
    public function restricted()
    {
        $this->request->allowMethod('post');
        // your other logic will be here
        // and finally set your response
        // $this->apiResponse['you_response'] = 'your response data';
    }
}
```

The response will look like,
```json
{"status":"NOK","result":{"message":"Method Not Allowed"}}
```

Another example of throwing an exception,

```php
<?php

namespace App\Controller;

use Cake\Network\Exception\NotFoundException;
use RestApi\Controller\ApiController;

/**
 * Foo Controller
 *
 */
class FooController extends ApiController
{

    /**
     * error method
     *
     */
    public function error()
    {
        $throwException = true;

        if ($throwException) {
            throw new NotFoundException();
        }

        // your other logic will be here
        // and finally set your response
        // $this->apiResponse['you_response'] = 'your response data';
    }
}
```

And the response will be,

```json
{"status":"NOK","result":{"message":"Not Found"}}
```
## Reporting Issues
If you have a problem with this plugin or any bug, please open an issue on [GitHub](https://github.com/multidots/cakephp-rest-api/issues).
