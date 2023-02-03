<?php

namespace Orders;


class OrderProcessor
{

	private $validator;
	/**
	 * @var OrderDeliveryDetails
	 */
	private $orderDeliveryDetails;

	public function __construct(OrderDeliveryDetails $orderDeliveryDetails)
	{
		$this->orderDeliveryDetails = $orderDeliveryDetails;
		$this->validator = OrderValidator::create();
	}

	/**
	 * @param $order Order
	 */
	public function process($order)
	{
		ob_start();
		echo "Processing started, OrderId: {$order->order_id}\n";
		$this->validator->validate($order);
		if ($this->validator->isValid()) {
			echo "Order is valid\n";
			$this->addDeliveryCostLargeItem($order);
			if ($order->is_manual) {
				echo "Order \"" . $order->order_id . "\" NEEDS MANUAL PROCESSING\n";
			} else {
				echo "Order \"" . $order->order_id . "\" WILL BE PROCESSED AUTOMATICALLY\n";
			}
			$deliveryDetails = $this->orderDeliveryDetails::getDeliveryDetails(count($order->items));
			$order->setDeliveryDetails($deliveryDetails);
		} else {
			echo "Order is invalid\n";
		}

		$this->printToFile($order);
	}

	/**
	 * @param $order Order
	 */
	public function addDeliveryCostLargeItem($order)
	{
		foreach ($order->items as $item) {
			if (in_array($item, [3231, 9823])) {
				$order->totalAmount = $order->totalAmount + 100;
			}
		}
	}

	private function printToFile($order)
	{
		$result = ob_get_contents();
		ob_end_clean();

		if ($this->validator->isValid()) {
			$lines = explode("\n", $result);
			$lineWithoutDebugInfo = [];
			foreach ($lines as $line) {
				if (strpos($line, 'Reason:') === false) {
					$lineWithoutDebugInfo[] = $line;
				}
			}
		}

		file_put_contents('orderProcessLog', @file_get_contents('orderProcessLog') . implode("\n", $lineWithoutDebugInfo ?? [$result] ));
		if ($this->validator->isValid()) {
			file_put_contents('result', @file_get_contents('result') . $order->order_id . '-' . implode(',', $order->items) . '-' . $order->deliveryDetails . '-' . ($order->is_manual ? 1 : 0) . '-' . $order->totalAmount . '-' . $order->name . "\n");
		}
	}
}