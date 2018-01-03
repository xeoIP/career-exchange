<?php

namespace App\Helpers;

use App\Models\Post;
use App\Models\Package;
use App\Models\Payment as PaymentModel;
use App\Mail\PaymentNotification;
use App\Notifications\PaymentSent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Session;

class Payment
{
    public static $country;
    public static $lang;
    public static $msg = [];
    public static $uri = [];

    /**
     * Apply actions after successful Payment
     *
     * @param $params
     * @param $post
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public static function paymentConfirmationActions($params, $post)
    {
        // Save the Payment in database
        $payment = self::register($post, $params);

        // Successful transaction
        flash(self::$msg['checkout']['success'])->success();

        // Redirect
        session()->flash('message', self::$msg['post']['success']);

        return redirect(self::$uri['nextUrl']);
    }

    /**
     * Apply actions when Payment failed
     *
     * @param $post
     * @param null $errorMessage
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public static function paymentFailureActions($post, $errorMessage = null)
    {
        // Remove the entry
        self::removeEntry($post);

        // Return to Form
        $message = '';
        $message .= self::$msg['checkout']['error'];
        if (!empty($errorMessage)) {
            $message .= '<br>' . $errorMessage;
        }
        flash($message)->error();

        // Redirect
        return redirect(self::$uri['previousUrl'] . '?error=payment')->withInput();
    }

    /**
     * Apply actions when API failed
     *
     * @param $post
     * @param $exception
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public static function paymentApiErrorActions($post, $exception)
    {
        // Remove the entry
        self::removeEntry($post);

        // Remove local parameters into the session (if exists)
        if (Session::has('params')) {
            Session::forget('params');
        }

        // Return to Form
        flash($exception->getMessage())->error();

        // Redirect
        return redirect(self::$uri['previousUrl'] . '?error=paymentApi')->withInput();
    }

    /**
     * Save the payment and Send payment confirmation email
     *
     * @param $post
     * @param $params
     * @return PaymentModel
     */
    public static function register(Post $post, $params)
    {
        if (empty($post)) {
            return null;
        }

        // Update ad 'reviewed'
        $post->reviewed = 1;
        $post->featured = 1;
        $post->save();

        // Save the payment
        $paymentInfo = [
            'post_id'           => $post->id,
            'package_id'        => $params['package_id'],
            'payment_method_id' => $params['payment_method'],
            'transaction_id'    => (isset($params['transaction_id'])) ? $params['transaction_id'] : null,
        ];
        $payment = new PaymentModel($paymentInfo);
        $payment->save();

        // SEND EMAILS

        // Get all admin users
        $admins = User::where('is_admin', 1)->get();

        // Send Payment Email Notifications
        if (config('settings.payment_email_notification') == 1) {
            // Send Confirmation Email
            try {
                $post->notify(new PaymentSent($payment, $post));
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }

            // Send to Admin the Payment Notification Email
            try {
                if ($admins->count() > 0) {
                    foreach ($admins as $admin) {
                        Mail::send(new PaymentNotification($payment, $post, $admin));
                    }
                }
            } catch (\Exception $e) {
                flash($e->getMessage())->error();
            }
        }

        return $payment;
    }

    /**
     * Remove the ad for public - If there are no free packages
     *
     * @param Post $post
     * @return bool
     */
    public static function removeEntry(Post $post)
    {
        if (empty($post)) {
            return false;
        }

        // Don't delete the ad when user try to UPGRADE her ads
        if (empty($post->tmp_token)) {
            return false;
        }

        if (Auth::check()) {
            // Delete the ad if user is logged in and there are no free package
            if (Package::where('price', 0)->count() == 0) {
                // But! User can access to the ad from her area to UPGRADE it!
                // You can UNCOMMENT the line below if you don't want the feature above.
                // $post->delete();
            }
        } else {
            // Delete the ad if user is a guest
            $post->delete();
        }

        return true;
    }
}
