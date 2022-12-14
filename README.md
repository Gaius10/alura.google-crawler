# Google Crawler

> This is a study-made project based on the one available
> [here](https://github.com/CViniciusSDias/google-crawler/blob/master/README.md)

*My certificate, issued by Alura:*

![Meu certificado](certificate.png)

*Project description:*

A simple Crawler for getting Google results.

This component can be used to retrieve the 100 first results for a search term.

Since google detects a crawler and blocks the IP when several requests are made,
this component is prepared to use some online proxy services, such as hide.me.

> TODO: finish tests to achieve 100% of code coverage

## Installation

Install the latest version with

```bash
composer require cviniciussdias/google-crawler
```

## Usage

### Crawler class constructor prototype

```php
CViniciusSDias\GoogleCrawler\Crawler::__construct(
    SearchTermInterface $searchTerm, GoogleProxyInterface $proxy = null,
    string $googleDomain = 'google.com', string $countryCode = ''
)
```

#### Parameters

- $searchTerm Term that will be searched on Google
- $proxy Online proxy service that will be used to access Google [optional]
- $googleDomain Your country specific google domain, like google.de, google.com.br, etc. [optional]
- $countryCode Country code that will be added to `gl` parameter on Google's url, indicating the location of the search. E.g. 'BR', 'US', 'DE' [optional]

## Examples

### Without proxy

```php
<?php
use CViniciusSDias\GoogleCrawler\{
    Crawler, SearchTerm
};

$searchTerm = new SearchTerm('Test');
$crawler = new Crawler($searchTerm); // or new Crawler($searchTerm, new NoProxy());

$resultList = $crawler->getResults();
```

### With some proxy

```php
<?php
use CViniciusSDias\GoogleCrawler\{
    Crawler, SearchTerm, Proxy\CommonProxy
};

$searchTerm = new SearchTerm('Test');
$commonProxy = new CommonProxy('https://us.hideproxy.me/includes/process.php?action=update');
$crawler = new Crawler($searchTerm, $commonProxy);

$resultList = $crawler->getResults();
```

#### More details on proxies

To know more details about which proxies are currently
supported, see the files inside `tests/Functional` folder.
There you'll see all the available proxies.

### Iterating over results

```php
foreach ($resultList as $result) {
    $title = $result->getTitle();
    $url = $result->getUrl();
    $description = $result->getDescription();
}
```

## About

### Requirements

- This component works with PHP 7.2 or above
- This component requires the extension [php-ds](http://php.net/manual/pt_BR/book.ds.php) to be installed

### Author

Vinicius Dias (ZCE) - carlosv775@gmail.com - https://github.com/CViniciusSDias/ - http://www.zend.com/en/yellow-pages/ZEND030134

### License

This component is licensed under the GPL v3.0 License - see the `LICENSE` file for details
