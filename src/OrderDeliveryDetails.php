<?php
/**
 * @author Timofey Khokhlovskii <timofey.khokhlovskii@internetstores.com>
 */

namespace Orders;


class OrderDeliveryDetails
{
	public static function getDeliveryDetails($productsCount)
	{
		if ($productsCount > 1) {
			return 'Order delivery time: 2 days';
		} else {
			return 'Order delivery time: 1 day';
		}
	}
}