README 
======

What is Checkdomain/TeleCash
---------------------------
Checkdomain/TeleCash is a wrapper for the [TeleCash Internet Payment Gateway](http://www.telecash.de/produkte-services/e-commerce/support-fuer-entwickler/ipg-schnittstellen-fuer-entwickler/) (german). TeleCash Internet Payment Gateway (TeleCash IPG) is a payment service provider supporting a variety of payment options. 

This project focuses on version 4 of the **API** interface (IPG API). There is no support for the **Connect** interface. 

Checkdomain/TeleCash is currently limit to credit card payments.

TeleCash offers a service called [DataStorage](http://www.telecash.de/produkte-services/e-commerce/internet-payment-gateway/ipg-add-ons/) (german), which basically means they handle the secure storage of credit card information. Checkdomain/TeleCash supports the **DataStorage** service, in fact it is the only supported option for initiating sell transactions.

Requirements
------------
Checkdomain/TeleCash requires php >= 5.4.

Installation
------------
The easiest way to install this library is through [composer](http://getcomposer.org/). Just add the following lines to your **composer.json** file:

```json
{
   "require": {
        "checkdomain/TeleCash": "dev-master"
    }
}
```

Another way would be to download this library and configure the autoloading yourself. This library relies on a [PSR-0](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md) compatible autoloader for automatic class loading.

Usage
-----
All supported API functions are accessible via the `TeleCash` class. For example, to validate credit card information use the `validate` method:

```php
$teleCash = new Checkdomain\TeleCash\TeleCash(...);
$validation = $teleCash->validate($ccNumber, $ccValid);
echo $validation->wasSuccessful();
```

To store credit card information using the **DataStorage** service use the `storeHostedData` method:

```php
$response = $teleCash->storeHostedDate($ccNumber, $ccValid, $hostedDataId);
```

To make a sell using previously stored credit card information use the `sellUsingHostedData` method:

```php
$response = $teleCash->sellUsingHostedData($hostedDataId, $amount);
```

Contributing
------------
Checkdomain/TeleCash is open source. We are happily accepting any kind of contributions.

Running Tests
-------------
Run a `php composer.phar install` command in the base directory to install the `phpunit` dependency. After that you can simply call `vendor/bin/phpunit tests/` to run the test suite.
