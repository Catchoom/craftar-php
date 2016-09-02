# CraftAR PHP Library


## Description

The CraftAR Service for [Augmented Reality and Image Recognition](http://catchoom.com/product/craftar/augmented-reality-and-image-recognition/) is a  service
that allows you to build a wide range of __Image Recognition__ and __Augmented Reality__ applications
and services.

With CraftAR, you can create amazing apps that provide digital content
for real-life objects like printed media, packaging among others. You
can use our online web panel or APIs, to upload images to be recognized and set
content to display upon recognition in your CraftAR-powered app.

This client library provides access to CraftAR APIs:
- [CraftAR Image Recognition API]
(http://catchoom.com/documentation/api/recognition/)
  allows image recognition against one of your _collections_ of _reference images_ specified using the collection _token_.
- [CraftAR Management API]
(http://catchoom.com/documentation/api/management/)
  allows upload and management of _collections_ of _reference images_, and associated meta-data such as _Augmented Reality experiences_ and their content.
  All requests must be authenticated using your _Management API key_.


## Dependencies

- PHP >= 5.3
- [PHP-cURL](http://php.net/manual/es/book.curl.php)

## Quick Start

See the example.php file

## Installation

You can install the library using [Composer](https://getcomposer.org/) and it will automatically download the library,
check the dependencies and load the classes when used in your code. Just run:

``` sh
composer require "catchoom/craftar-php" "dev-master"
```

## Reporting Issues

If you have suggestions, bugs or other issues specific to this library, file
them [here](https://github.com/Catchoom/craftar-php/issues) or contact us
at [support@catchoom.com](mailto:support@catchoom.com).
