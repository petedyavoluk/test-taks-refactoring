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
            $this->printOrder($order);
		} else {
			echo "Order is invalid\n";
		}

        $log = ob_get_contents();
        ob_end_clean();

        if ($this->validator->isValid()) {
            $log = $this->removeDebugInfo($log);
        }

		$this->printToFile($log);
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

    /**
     * @param $log
     * @return void
     */
	private function printToFile($log)
	{
		file_put_contents('orderProcessLog', $log, FILE_APPEND);
	}

    /**
     * @param $order
     * @return void
     */
    private function printOrder($order){
        file_put_contents('result', $order->order_id . '-' . implode(',', $order->items) . '-' . $order->deliveryDetails . '-'
            . ($order->is_manual ? 1 : 0) . '-' . $order->totalAmount . '-' . $order->name . "\n", FILE_APPEND);
    }

    /**
     * @param $log
     * @return string
     */
    private function removeDebugInfo($log){
        $lines = explode("\n", $log);
        $lineWithoutDebugInfo = [];
        foreach ($lines as $line) {
            if (strpos($line, 'Reason:') === false) {
                $lineWithoutDebugInfo[] = $line;
            }
        }
        return implode("\n", $lineWithoutDebugInfo ?? [$log] );
    }
}