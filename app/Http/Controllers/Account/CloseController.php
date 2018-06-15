<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;

class CloseController extends AccountBaseController
{
	/**
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
	 */
    public function index()
    {
		view()->share('pagePath', 'close');
        return view('account.close');
    }

	/**
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
	 */
    public function submit()
    {
        if (Request::input('close_account_confirmation') == 1) {
            // Get User
            $user = User::find($this->user->id);
            if (is_null($user)) {
                abort(404);
            }

            // Don't delete admin users
            if ($user->is_admin or $user->is_admin == 1) {
                flash("Admin users can't be deleted by this way.")->error();
                return redirect(config('app.locale') . '/account');
            }

            // Delete User
            $user->delete();

            // Close User's session
            Auth::logout();

            $message = t("Your account has been deleted. We regret you. <a href=\":url\">Re-register</a> if that is a mistake.", [
                'url' => lurl(trans('routes.register'))
            ]);
            flash($message)->success();
        }

        return redirect(config('app.locale') . '/');
    }
}
