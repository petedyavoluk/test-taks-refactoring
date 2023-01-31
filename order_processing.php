<?php

use Orders\Order;
use Orders\OrderDeliveryDetails;
use Orders\OrderProcessor;

require_once 'vendor/autoload.php';

$order = new Order();
$order->setOrderId(2);
$order->setName('John');
$order->setItems([ 6654 ]);
$order->setTotalAmount(346.2);

$orderProcessor = new OrderProcessor(new OrderDeliveryDetails());
$orderProcessor->process($order);
