<?php
require __DIR__ . '/include_all.php';

// Replace this with your actual api key
// Or just set SENDWORKS_API_KEY as an env variable
$api_key = isset($_SERVER['SENDWORKS_API_KEY']) ? $_SERVER['SENDWORKS_API_KEY'] : 'YOUR_KEY_HERE';

// Connect to sandbox, set debug to dump all http to stdout
$sendworks = new Sendworks\Connection($api_key, 'api.sandbox.sendworks.com', ['debug' => true]);
// By default, the built-in http client is used, but you can override this to use \GuzzleHttp\Client instead, if you please.
// $sendworks = new Sendworks\Connection($api_key, 'api.sandbox.sendworks.com', ['http_client' => '\GuzzleHttp\Client', 'debug' => true]);
// Local testing
// $sendworks = new Sendworks\Connection($api_key, 'localhost:3000', ['debug' => true]);

// Get a quote for a shipment
$recipient = new Sendworks\Address(['post_code' => 2860, 'country_code' => 'DK']);
$order = new Sendworks\Order(['subtotal' => '200 DKK']);
var_dump($sendworks->products->select($recipient, $order));

// Get all service points for a product
$recipient = new Sendworks\Address(['post_code' => 2860, 'country_code' => 'DK']);
$products = $sendworks->products->select();
var_dump($sendworks->service_points->select($products[0], $recipient));

// List orders
var_dump($sendworks->orders->select());

// Get shipment from order
$shipment = $sendworks->orders->select()[0]->shipments[0]->resolve();
var_dump($shipment);

// List shipments
var_dump($sendworks->shipments->select());

// Get label from shipment
foreach ($sendworks->orders->select() as $order) {
  foreach ($order->shipments as $shipment) {
    foreach ($shipment->labels as $label) {
      $label->write($label->filename);
    }
  }
}

/*
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
echo "Select valid products for shipment\n";
$products = $sendworks->products->select($recipient);
var_dump($products[0]);
$shipment = new Sendworks\Shipment(['recipient' => $recipient, 'parcels' => [$parcel], 'product' => $products[0]]);
$shipment->shipment_reference = "TEST:" . time();
echo "Create shipment\n";
$shipment = $sendworks->shipments->save($shipment);
var_dump($shipment);
echo "Purchase shipment\n";
if ($shipment->allowPurchase()) {
  $shipment = $sendworks->shipments->buy($shipment);
  var_dump($shipment);
}
*/
