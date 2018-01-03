<?php

namespace App\Plugins\paypal;

use App\Models\Post;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Helpers\Payment;
use App\Models\Package;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;
use Illuminate\Support\Facades\Route;

class Paypal extends Payment
{
    /**
     * Send Payment
     *
     * @param Request $request
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public static function sendPayment(Request $request, Post $post)
    {
        // Set URLs
        parent::$uri['previousUrl'] = str_replace(['#entryToken', '#entryId'], [$post->tmp_token, $post->id], parent::$uri['previousUrl']);
        parent::$uri['nextUrl'] = str_replace(['#entryToken', '#entryId', '#title'], [$post->tmp_token, $post->id, slugify($post->title)], parent::$uri['nextUrl']);
        parent::$uri['paymentCancelUrl'] = str_replace(['#entryToken', '#entryId'], [$post->tmp_token, $post->id], parent::$uri['paymentCancelUrl']);
        parent::$uri['paymentReturnUrl'] = str_replace(['#entryToken', '#entryId'], [$post->tmp_token, $post->id], parent::$uri['paymentReturnUrl']);
        
        // Get Pack infos
        $package = Package::find($request->input('package'));
        
        // Don't make a payment if 'price' = 0 or null
        if (empty($package) || $package->price <= 0) {
            return redirect(parent::$uri['previousUrl'] . '?error=package')->withInput();
        }
    
        // API Parameters
        $providerParams = [
            'cancelUrl'   => parent::$uri['paymentCancelUrl'],
            'returnUrl'   => parent::$uri['paymentReturnUrl'],
            'name'        => $package->name,
            'description' => $package->name,
            'amount'      => (!is_float($package->price)) ? floatval($package->price) : $package->price,
            'currency'    => $package->currency_code,
        ];
    
        // Local Parameters
        $localParams = [
            'payment_method' => $request->get('payment_method'),
            'post_id'        => $post->id,
            'package_id'     => $package->id,
        ];
        $localParams = array_merge($localParams, $providerParams);
    
        // Try to make the Payment
        try {
            $gateway = Omnipay::create('PayPal_Express');
            $gateway->setUsername(config('payment.paypal.username'));
            $gateway->setPassword(config('payment.paypal.password'));
            $gateway->setSignature(config('payment.paypal.signature'));
            $gateway->setTestMode((config('payment.paypal.mode') == 'sandbox') ? true : false);
            
            // Card Data
            // $providerParams['card'] = [];
    
            // Make the payment
            $response = $gateway->purchase($providerParams)->send();
    
            // Save the Transaction ID at the Provider
            $localParams['transaction_id'] = $response->getTransactionId();
    
            // Save local parameters into session
            Session::put('params', $localParams);
            Session::save();
            
            // Payment by Credit Card when Card info are provide from the form.
            if ($response->isSuccessful()) {
                
                // Check if redirection to offsite payment gateway is needed
                if ($response->isRedirect()) {
                    return $response->redirect();
                }
    
                // Apply actions after successful Payment
                return self::paymentConfirmationActions($localParams, $post);
                
            } elseif ($response->isRedirect()) {
                
                // Redirect to offsite payment gateway
                // Redirect to success URL to make the payment on the Paypal website
                return $response->redirect();
                
            } else {
                
                // Apply actions when Payment failed
                return parent::paymentFailureActions($post, $response->getMessage());
                
            }
        } catch (\Exception $e) {
            
            // Apply actions when API failed
            return parent::paymentApiErrorActions($post, $e);
            
        }
    }
    
    /**
     * @param $params
     * @param $post
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public static function paymentConfirmation($params, $post)
    {
        // Set form page URL
        parent::$uri['previousUrl'] = str_replace(['#entryToken', '#entryId'], [$post->tmp_token, $post->id], parent::$uri['previousUrl']);
        parent::$uri['nextUrl'] = str_replace(['#entryToken', '#entryId', '#title'], [$post->tmp_token, $post->id, slugify($post->title)], parent::$uri['nextUrl']);
        
        // Try to make the Payment
        try {
            $gateway = Omnipay::create('PayPal_Express');
            $gateway->setUsername(config('payment.paypal.username'));
            $gateway->setPassword(config('payment.paypal.password'));
            $gateway->setSignature(config('payment.paypal.signature'));
            $gateway->setTestMode((config('payment.paypal.mode') == 'live') ? false : true);
            
            // Make the payment
            $response = $gateway->completePurchase($params)->send();
            
            // Get raw data
            $rawData = $response->getData();
            
            // Check the Payment
            if (isset($rawData['PAYMENTINFO_0_ACK']) && $rawData['PAYMENTINFO_0_ACK'] === 'Success') {
    
                // Save the Transaction ID at the Provider (CORRELATIONID | PAYMENTINFO_0_TRANSACTIONID)
                if (isset($rawData['PAYMENTINFO_0_TRANSACTIONID'])) {
                    $params['transaction_id'] = $rawData['PAYMENTINFO_0_TRANSACTIONID'];
                }
                
                // Apply actions after successful Payment
                return parent::paymentConfirmationActions($params, $post);
                
            } else {
                
                // Apply actions when Payment failed
                return parent::paymentFailureActions($post);
                
            }
        } catch (\Exception $e) {
            
            // Apply actions when API failed
            return parent::paymentApiErrorActions($post, $e);
            
        }
    }
    
    /**
     * @return bool
     */
    public static function installed()
    {
        $paymentMethod = PaymentMethod::active()->where('name', 'LIKE', 'paypal')->first();
        if (empty($paymentMethod)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @return bool
     */
    public static function install()
    {
        // Remove the plugin entry
        self::uninstall();
        
        // Plugin data
        $data = [
            'id'           => 1,
            'name'         => 'paypal',
            'display_name' => 'Paypal',
            'description'  => 'Payment with Paypal',
            'has_ccbox'    => 0,
            'lft'          => 0,
            'rgt'          => 0,
            'depth'        => 1,
            'active'       => 1,
        ];
        
        try {
            // Create plugin data
            $paymentMethod = PaymentMethod::create($data);
            if (empty($paymentMethod)) {
                return false;
            }
        } catch (\Exception $e) {
            return false;
        }
        
        return true;
    }
    
    /**
     * @return bool
     */
    public static function uninstall()
    {
        $deletedRows = PaymentMethod::where('name', 'LIKE', 'paypal')->delete();
        if ($deletedRows <= 0) {
            return false;
        }
        
        return true;
    }
}
