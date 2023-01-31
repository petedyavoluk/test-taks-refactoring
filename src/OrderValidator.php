<?php

namespace Orders;


class OrderValidator
{
	public $minimumAmount;

	public function setMinimumAmount(int $amount)
	{
		$this->minimumAmount = $amount;
	}

    public static function create()
    {
    	$validator = new self();
	    $validator->setMinimumAmount(file_get_contents('input/minimumAmount'));
    	return $validator;
    }

	/**
	 * @param $order Order
	 */
    public function validate($order)
    {
	    $is_valid = true;
	    if (!is_string($order->name) || !(strlen($order->name) > 2) || !($order->totalAmount > 0) || $order->totalAmount < $this->minimumAmount) {
		    $is_valid = false;
	    }

	    foreach ($order->items as $item_id) {
		    if (!is_int($item_id)) {
			    $is_valid = false;
		    }
	    }

	    $order->is_valid = $is_valid;
    }
}