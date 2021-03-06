Charcoal Contrib Formio
===============

[![License][badge-license]][charcoal-contrib-formio]
[![Latest Stable Version][badge-version]][charcoal-contrib-formio]
[![Code Quality][badge-scrutinizer]][dev-scrutinizer]
[![Coverage Status][badge-coveralls]][dev-coveralls]
[![Build Status][badge-travis]][dev-travis]

A [Charcoal][charcoal-app] Charcoal service provider to add a form builder property input using form.io.



## Table of Contents

-   [Installation](#installation)
    -   [Dependencies](#dependencies)
-   [Service Provider](#service-provider)
    -   [Parameters](#parameters)
    -   [Services](#services)
-   [Configuration](#configuration)
-   [Usage](#usage)
-   [Development](#development)
    -  [API Documentation](#api-documentation)
    -  [Development Dependencies](#development-dependencies)
    -  [Coding Style](#coding-style)
-   [Credits](#credits)
-   [License](#license)



## Installation

The preferred (and only supported) method is with Composer:

```shell
$ composer require locomotivemtl/charcoal-contrib-formio
```

### Dependencies

#### Required

-   [**PHP 5.6+**](https://php.net): _PHP 7_ is recommended.



## Configuration

Add the formio module to the modules list in the site's config file.

```json
"modules": {
    "charcoal/formio/formio": {}
}
```



## Usage

Three property type are provided by this package :
- `formio/form` (Form builder input)
- `formio/schema` (Form schema)
- `formio/submission` (To save a form submission)
    
```json
"my_property": {
    "type": "formio/form",
    "l10n": false,
    "label": "My form property"
},
```


```json
"my_property": {
    "type": "formio/schema",
    "l10n": false,
    "label": "My schema property"
},
```

```json
"my_property_submission": {
      "type": "formio/submission",
      "l10n": false,
      "label": "My submission property"
}
```

<small>Note that formio properties do not support `l10n` for now.</small>

To update a form when saving the object you must call the method `createOrUpdateRelation()` on the property.
This example saves or updates the form builder schema for a property called `test`. This code should be found in the object's controller.
```php
/**
 * {@inheritdoc}
 *
 * @see StorableTrait::preSave()
 * @return boolean
 */
protected function preSave()
{
    $this->test = $this->p('test')->createOrUpdateRelation($this->test, null);

    return parent::preSave();
}

/**
 * {@inheritdoc}
 *
 * @see StorableTrait::preUpdate
 * @param array $properties Optional properties to update.
 * @return boolean
 */
protected function preUpdate(array $properties = null)
{
    $clone = clone $this; // Avoid calling `load()` on current object.
    $this->test = $this->p('test')->createOrUpdateRelation($this->test, $clone->load()->test);

    return parent::preUpdate($properties);
}
```

## Development

To install the development environment:

```shell
$ composer install
```

To run the scripts (phplint, phpcs, and phpunit):

```shell
$ composer test
```

## Resources

-   The Form rendering documentation. Useful for front-end rendering of stored form.io structures.
    [https://github.com/formio/formio.js/wiki/Form-Renderer](https://github.com/formio/formio.js/wiki/Form-Renderer)

-   The Form.io sdk documentation.
    [http://formio.github.io/formio.js/app/sdk](http://formio.github.io/formio.js/app/sdk)

-   Front-end frameworks.
    [https://help.form.io/developer/frameworks](https://help.form.io/developer/frameworks)

### API Documentation

-   The auto-generated `phpDocumentor` API documentation is available at:  
    [https://locomotivemtl.github.io/charcoal-contrib-formio/docs/master/](https://locomotivemtl.github.io/charcoal-contrib-formio/docs/master/)
-   The auto-generated `apigen` API documentation is available at:  
    [https://codedoc.pub/locomotivemtl/charcoal-contrib-formio/master/](https://codedoc.pub/locomotivemtl/charcoal-contrib-formio/master/index.html)



### Development Dependencies

-   [php-coveralls/php-coveralls][phpcov]
-   [phpunit/phpunit][phpunit]
-   [squizlabs/php_codesniffer][phpcs]



### Coding Style

The charcoal-contrib-formio module follows the Charcoal coding-style:

-   [_PSR-1_][psr-1]
-   [_PSR-2_][psr-2]
-   [_PSR-4_][psr-4], autoloading is therefore provided by _Composer_.
-   [_phpDocumentor_](http://phpdoc.org/) comments.
-   [phpcs.xml.dist](phpcs.xml.dist) and [.editorconfig](.editorconfig) for coding standards.

> Coding style validation / enforcement can be performed with `composer phpcs`. An auto-fixer is also available with `composer phpcbf`.



## Credits

-   [Locomotive](https://locomotive.ca/)



## License

Charcoal is licensed under the MIT license. See [LICENSE](LICENSE) for details.



[charcoal-contrib-formio]:  https://packagist.org/packages/locomotivemtl/charcoal-contrib-formio
[charcoal-app]:             https://packagist.org/packages/locomotivemtl/charcoal-app

[dev-scrutinizer]:    https://scrutinizer-ci.com/g/locomotivemtl/charcoal-contrib-formio/
[dev-coveralls]:      https://coveralls.io/r/locomotivemtl/charcoal-contrib-formio
[dev-travis]:         https://travis-ci.org/locomotivemtl/charcoal-contrib-formio

[badge-license]:      https://img.shields.io/packagist/l/locomotivemtl/charcoal-contrib-formio.svg?style=flat-square
[badge-version]:      https://img.shields.io/packagist/v/locomotivemtl/charcoal-contrib-formio.svg?style=flat-square
[badge-scrutinizer]:  https://img.shields.io/scrutinizer/g/locomotivemtl/charcoal-contrib-formio.svg?style=flat-square
[badge-coveralls]:    https://img.shields.io/coveralls/locomotivemtl/charcoal-contrib-formio.svg?style=flat-square
[badge-travis]:       https://img.shields.io/travis/locomotivemtl/charcoal-contrib-formio.svg?style=flat-square

[psr-1]:  https://www.php-fig.org/psr/psr-1/
[psr-2]:  https://www.php-fig.org/psr/psr-2/
[psr-3]:  https://www.php-fig.org/psr/psr-3/
[psr-4]:  https://www.php-fig.org/psr/psr-4/
[psr-6]:  https://www.php-fig.org/psr/psr-6/
[psr-7]:  https://www.php-fig.org/psr/psr-7/
[psr-11]: https://www.php-fig.org/psr/psr-11/
