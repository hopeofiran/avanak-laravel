# Laravel Avanak API service 
**This package helps us to use Avanak services.**

## installation 
> *To install from `composer` use the following command*
```shell script
composer require hopeofiran/avanak-laravel
```
## configuration `.env` file
> *add account information into `.env` file*
```shell script
AVANAK_USERNAME=your_username
AVANAK_PASSWORD=your_password
```
## to change default configuration
>to access config file and change default configuration use following command
```shell script
php artisan vendor:publish --provider=hopeofiran\avanak\Providers\AvanakProvider
```
> *or you can set config with `config($keys[,$value])` method*

```php
$credit = \hopeofiran\avanak\Facades\AvanakFacade::config('username', "{$your_username}")
->config('password', "{$your_password}")
->getCredit();
```
### OR
```php
$credit = \hopeofiran\avanak\Facades\AvanakFacade::config(['username'=>"{$your_username}", 'password' => "{$your_password}"])
->getCredit();
```

> *also you can modify `avanak` server address with `baseUrl($url)` method*
```php
$credit = \hopeofiran\avanak\Facades\AvanakFacade::baseUrl("$avanak_new_address")
->getCredit();
```

# How to use this package
### CreateCampaign method
*to create campaign use `createCampaign()` method*
```php
$title              = 'example title';
$numbers            = ['0912×××6789', '0911×××1111'];
$maxTryCount        = 2;
$minuteBetweenTries = 1;
$start              = now()->addMicros(5);
$end                = now()->endOfDay();
$messageId          = 123456789;
$campaign = \hopeofiran\avanak\Facades\AvanakFacade::createCampaign($title, $numbers, $maxTryCount, $minuteBetweenTries, $start, $end, $messageId);
```
> response
```json
{
  "CreateCampaignResult": 123456789
}
```

### QuickSend method
> *to quick send message use `quickSend()` method*
```php
$number    = '0912×××6789';
$messageId = 123456789;
$response = \hopeofiran\avanak\Facades\AvanakFacade::quickSend($number, $messageId);
```
> response
```json
{
  "QuickSendResult": 123456789
}
```