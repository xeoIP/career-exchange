<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Larapen\Admin\app\Http\Controllers\Controller;
use Prologue\Alerts\Facades\Alert;

class CacheController extends Controller
{
	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function clear()
	{
		$errorFound = false;

		// Removing all Objects Cache
		try {
			$exitCode = Artisan::call('cache:clear');
		} catch (\Exception $e) {
			Alert::success($e->getMessage())->flash();
			$errorFound = true;
		}

		// Some time of pause
		sleep(2);

		// Removing all Views Cache
		try {
			$exitCode = Artisan::call('view:clear');
		} catch (\Exception $e) {
			Alert::success($e->getMessage())->flash();
			$errorFound = true;
		}

		// Some time of pause
		sleep(1);

		// Removing all Logs
		try {
			File::delete(File::glob(storage_path('logs') . '/laravel*.log'));
		} catch (\Exception $e) {
			Alert::success($e->getMessage())->flash();
			$errorFound = true;
		}

		// Check if error occurred
		if (!$errorFound) {
			$message = __t("The cache was successfully dumped.");
			Alert::success($message)->flash();
		}

		return back();
	}
}
