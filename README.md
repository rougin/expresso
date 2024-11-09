# Expresso

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]][link-license]
[![Build Status][ico-build]][link-build]
[![Total Downloads][ico-downloads]][link-downloads]

This is the skeleton project for the [Expresso](https://github.com/staticka/expresso) package which also provides the [Console](https://github.com/staticka/console) package for building Markdown pages.

## Installation

Create a new project for `Expresso` via [Composer](https://getcomposer.org/):

``` bash
$ composer create-project rougin/expresso
```

## Basic Usage

As this is a project template, kindly see the documentation for each required package in this project for more information:

* [Console Documentation][link-console-readme]
* [Expresso Documentation][link-expresso-readme]

### Running the application

To run the application, the [PHP's built-in web server](https://www.php.net/manual/en/features.commandline.webserver.php) can be used:

``` bash
$ php -S localhost:3977 -t app/public
```

Once the application has been initialized, kindly see the instructions found in the `Dashboard` page of the application.

### Building the created pages

To build the created pages to HTML, kindly run the `build` command from `staticka`:

``` bash
$ vendor/bin/staticka build
```

Alternatively, it is also possible to build pages from the application by selecting the green `Build site` button which can be found from the upper right of the web browser.

## Changelog

Please see [CHANGELOG][link-changelog] for more information what has changed recently.

## Credits

- [All contributors][link-contributors]

## License

The MIT License (MIT). Please see [LICENSE][link-license] for more information.

[ico-build]: https://img.shields.io/github/actions/workflow/status/staticka/expresso/build.yml?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/staticka/expresso.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/staticka/expresso.svg?style=flat-square

[link-build]: https://github.com/staticka/expresso/actions
[link-changelog]: https://github.com/staticka/expresso/blob/master/CHANGELOG.md
[link-console-readme]: https://github.com/staticka/expresso/blob/master/README.md
[link-contributors]: https://github.com/staticka/expresso/contributors
[link-downloads]: https://packagist.org/packages/staticka/expresso
[link-expresso-readme]: https://github.com/staticka/expresso/blob/master/README.md
[link-license]: https://github.com/staticka/expresso/blob/master/LICENSE.md
[link-packagist]: https://packagist.org/packages/staticka/expresso