#sendworks-php

Sendworks API wrapper for PHP

This library wraps the Sendworks REST API in an object-oriented PHP interface. For a full specification of the REST API, please have a look at [https://sendworks.com/documentation/](https://sendworks.com/documentation/)

##Usage

You can use the library by cloning this repository into a subfolder of your application and include the file [`include_all.php`](https://github.com/sendworks/sendworks-php/blob/master/include_all.php).

Alternatively, you can use [Composer](https://getcomposer.org/) to load the library. Here's a sample composer.json file:

```json
{
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/sendworks/sendworks-php"
    }
  ],
  "require": {
    "sendworks/sendworks-php" : "master"
  }
}
```

##Getting started

To use, first create a connection instance, using your api key:

```php
<?php
require '/path/to/sendworks-php/include_all.php';
// Replace this with your actual api key
$api_key = 'YOUR_KEY_HERE';
// Connect to sandbox
$sendworks = new Sendworks\Connection($api_key, 'api.sandbox.sendworks.com');
```

This connects you to the test environment (sandbox). The sandbox is similar to the production setup, but nothing you do will actually be sent to the carriers and labels won't be valid.

You should replace `YOUR_KEY_HERE` with your actual api key.

You can now use the connection to get a quote:

```php
// Get a quote for a shipment
$recipient = new Sendworks\Address(['post_code' => 2860, 'country_code' => 'DK']);
$order = new Sendworks\Order(['subtotal' => '200 DKK']);
var_dump($sendworks->products->select($recipient, $order));
```

And to create a shipment:

```php
// Create a shipment
$recipient = new Sendworks\Address([
  'name' => 'Lorem von Ipsum',
  'street1' => 'Mars Alle 1',
  'post_code' => 2860,
  'city' => 'Søborg',
  'country_code' => 'DK',
  'email' => 'tkn@sendworks.com',
  'phone' => '12345678',
]);
$parcel = new Sendworks\Parcel([]);
$products = $sendworks->products->select($recipient);
$shipment = new Sendworks\Shipment(['recipient' => $recipient, 'parcels' => [$parcel], 'product' => $products[0]]);
$shipment->shipment_reference = "TEST:" . time();
$shipment = $sendworks->shipments->save($shipment);
var_dump($shipment);
```

See also [`examples.php`](https://github.com/sendworks/sendworks-php/blob/master/examples.php).
