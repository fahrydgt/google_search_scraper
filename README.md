# Get google result 

## Description
PHP library that allows you to scrape the google search results with query or keywords

# Installation & usage

* Create new directory and get in
* Import the google_search_scraper package with following command

## Composer

Install stable library version by using standard commands.

```bash
# Install PHP Library via Composer
composer require fahrydgt/google_search_scraper
```

* Create index.php in root folder and paste the following php code

```bash
<?php
use \FlScrapper\GoogleScraperPackage\SearchEngine;
require 'vendor/autoload.php';


$client = new SearchEngine();
$client->setEngine("google.ae");

# Result count default set to top 5 result (Optional)
$results = $client->search(["keyword1","keyword2"],5);

// Result will be print
echo "<pre>"; print_r($results);

?>
```

* Test the result using browser

## Note
Result count 50 may take long time (arount 1 to 2 mins)