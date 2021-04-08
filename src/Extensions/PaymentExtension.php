<?php

namespace SilverCommerce\Checkout\Extensions;

use SilverStripe\ORM\DataExtension;
use SilverCommerce\TaxAdmin\Helpers\MathsHelper;
use SilverCommerce\OrdersAdmin\Model\Invoice;
use SilverCommerce\OrdersAdmin\Model\Estimate;

/**
 * Add association to an order to payments and add
 * setting order status on capture.
 *
 * @author     Mo <morven@ilateral.co.uk>
 * @package    checkout
 * @subpackage extensions
 */
class PaymentExtension extends DataExtension
{
    private static $has_one = [
        'Invoice' => Invoice::class
    ];

    /**
     * Process attached order when payment is taken
     *
     * @param  ServiceResponse $response
     * @return void
     */
    public function onCaptured($response)
    {
        $order = $this->owner->Invoice();

        if ($order->exists()) {
            $payment_amount = MathsHelper::round($this->owner->getAmount(), 2);
            $order_amount = MathsHelper::round($order->Total, 2);

            // If our payment is the value of the order, mark paid
            // else mark part paid (unless order is zero value)
            if ($order_amount === 0.00 ||
                abs(($payment_amount - $order_amount) / $order_amount) < 0.00001
            ) {
                $order->markPaid();
            } else {
                $order->markPartPaid();
            }

            $order->write();
        }
    }

    /**
     * Process attached order when payment is refunded
     *
     * @param  ServiceResponse $response
     * @return void
     */
    public function onRefunded($response)
    {
        $order = $this->owner->Invoice();

        if ($order->exists()) {
            $order->markRefunded();
            $order->write();
        }
    }

    /**
     * Process attached order when payment is voided/cancelled
     *
     * @param  ServiceResponse $response
     * @return void
     */
    public function onVoid($response)
    {
        $order = $this->owner->Invoice();

        if ($order->exists()) {
            $order->markCancelled();
            $order->write();
        }
    }
}
