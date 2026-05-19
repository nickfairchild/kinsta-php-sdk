# Kinsta PHP SDK

![Tests](https://github.com/nickfairchild/kinsta-php-sdk/workflows/run-tests/badge.svg)

This package provides a PHP SDK for the Kinsta API, built with [Saloon](https://docs.saloon.dev/) v4.

```php
use Nickfairchild\KinstaPhpSdk\Kinsta;
use Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites;

$kinsta = new Kinsta('your-api-token', 'your-company-id');

// returns a collection of Nickfairchild\KinstaPhpSdk\Dto\Hosting\Sites
$sites = $kinsta->sites();

foreach ($sites as $site) {
    echo "Site: {$site->displayName} (ID: {$site->id})\n"
}
```

Behind the scenes, the SDK uses [Saloon](https://docs.saloon.dev/) to make the HTTP requests.

## Installation

```bash
composer require nickfairchild/kinsta-php-sdk
```

## Usage

To authenticate, you'll need an API token. You can create one in
[MyKinsta](https://my.kinsta.com/), go to your username > Company settings > API Keys.
You will also need your company ID. You can get one by going go to your username > Company settings > Billing details.

```php
use Nickfairchild\KinstaPhpSdk\Kinsta;

$kinsta = new Kinsta('your-api-token', 'your-company-id');
```
### Handling errors

The SDK will throw an exception if the API returns an error. For validation errors, the SDK will throw a `ValidationException`.

```php
try {
    $kinsta->createSite([
        'region' => 'invalid-region',
    ]);
} catch (\Nickfairchild\KinstaPhpSdk\Exceptions\ValidationException $exception) {
    $exception->getMessage(); // returns a string describing the errors
    
    $exception->errors(); // returns an array with all validation errors
}
```

For all other errors, the SDK will throw an `\Nickfairchild\KinstaPhpSdk\Exceptions\KinstaException`.
