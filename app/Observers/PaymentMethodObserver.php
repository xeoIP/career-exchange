<?php

namespace App\Observer;

use App\Models\PaymentMethod;

class PaymentMethodObserver
{
    /**
     * Listen to the Entry deleting event.
     *
     * @param  PaymentMethod $paymentMethod
     * @return void
     */
    public function deleting(PaymentMethod $paymentMethod)
    {
        // $paymentMethod->payment()->delete();
    }
}
