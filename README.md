# Cognetif Site Language
<img src="https://travis-ci.org/cognetif/site-language.svg?branch=master" alt="Build Status"/> <a href="/LICENSE"><img src="https://img.shields.io/badge/License-MIT-yellow.svg" alt="MIT LICENSE" /></a>

This library will attempt to detect a best match between the browsers accepted language from `$_SERVER['HTTP_ACCEPT_LANGUAGE']` and the provided array of website accepted languages.
If no match can be found the default fallback will be returned. If the server accept languages includes a locale like `fr-FR`, the `fr` will be used to match.



## Installation
Install with composer:

```bash
$ composer install cognetif/site-language
```

## How to Use

```php
use Cognetif\SiteLanguage\SiteLanguage;

$siteLanguage = new SiteLanguage();
$siteLanguage->setDefault('fr');
$siteLanguage->setAccepted(['en','fr']);

$languageToUse = $siteLanguage->get();
```

## Run Tests
```bash
$ ./vendor/bin/phpunit

```

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.


## Licence
MIT - See: [LICENCE](LICENSE)
