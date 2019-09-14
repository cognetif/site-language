# Cognetif Site Language
This library will attempt to detect a best match between the browsers accepted language array and the provided website accepted language array.
If no match can be found the default fallback will be returned.

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
$siteLanguage->setAcceptedLanguages(['en',fr']);

$languageToUse = $siteLanguage->getLanguage();
```

## Licence
MIT - See: [LICENCE](LICENSE)
