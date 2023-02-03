<?php

namespace Orders;


class OrderValidator
{
	public $minimumAmount;

    private $errors = [];

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
        $this->errors = [];
	    if (!is_string($order->name) || !(strlen($order->name) > 2)) {
            $this->errors[] = 'Name is invalid';
	    }

	    if ( !($order->totalAmount > 0) || $order->totalAmount < $this->minimumAmount) {
            $this->errors[] = 'Total amount is invalid';
	    }

	    foreach ($order->items as $item_id) {
		    if (!is_int($item_id)) {
                $this->errors[] = 'Order item is invalid';
		    }
	    }
    }

    public function getErrors(){
        return $this->errors;
    }

    public function isValid(){
        return count($this->errors) === 0;
    }
}