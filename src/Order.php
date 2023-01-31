<?php

namespace Orders;


class Order
{
	/**
	 * @var int
	 */
	public $order_id;
	/**
	 * @var bool
	 */
	public $is_manual = false;
	/**
	 * @var string
	 */
	public $name;
	/**
	 * @var array
	 */
	public $items;
	/**
	 * @var float
	 */
	public $totalAmount;
	/**
	 * @var string
	 */
	public  $deliveryDetails;

	/**
	 * @param string $name
	 */
	public function setName(string $name)
	{
		$this->name = $name;
	}

	/**
	 * @param array $items
	 */
	public function setItems(array $items)
	{
		$this->items = $items;
	}

	/**
	 * @param float $totalAmount
	 */
	public function setTotalAmount(float $totalAmount)
	{
		$this->totalAmount = $totalAmount;
	}

	/**
	 * @param int $order_id
	 */
	public function setOrderId(int $order_id)
	{
		$this->order_id = $order_id;
	}

	public $is_valid;

	public function setDeliveryDetails($deliveryDetails)
	{
		$this->deliveryDetails = $deliveryDetails;
	}
}