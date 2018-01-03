<?php

namespace App\Http\Middleware;

ini_set('max_execution_time', 300);

use App\Models\TimeZone;
use Closure;
use App\Models\Setting;

class InstallationChecker
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->segment(1) == 'install') {
            // Check if installation is processing
            $InstallInProgress = false;
            if (
                !empty($request->session()->get('database_imported')) ||
                !empty($request->session()->get('cron_jobs')) ||
                !empty($request->session()->get('install_finish'))
            )
            {
                $InstallInProgress = true;
            }
            if ($this->alreadyInstalled($request) && $this->properlyInstalled() && !$InstallInProgress) {
                return redirect('/');
            }
        } else {
			// Check if the website is installed
            if (!$this->alreadyInstalled($request) || !$this->properlyInstalled()) {
                return redirect($this->getBaseUrl() . '/install');
            } else {
				// Check if an update is available
				if ($this->checkUpdates()) {
					return headerLocation($this->getBaseUrl() . '/upgrade');
				}
			}
        }

        return $next($request);
    }

    /**
     * If application is already installed.
     *
     * @param $request
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public function alreadyInstalled($request)
    {
        // Check if installation has just finished
        $installHasJustFinished = false;
        if (!empty($request->session()->get('install_finish'))) {
            $installHasJustFinished = true;
        }

        if ($installHasJustFinished === true) {
            // Write file
            file_put_contents(storage_path('installed'), '');

            $request->session()->forget('install_finish');
            $request->session()->flush();

            // Redirect to the homepage after installation
            return redirect('/');
        }

        return file_exists(storage_path('installed'));
    }

    /**
     * @return bool
     */
    public function properlyInstalled()
    {
        // Check Installation Setup
        $properly = true;
        try {
            // Check if .env file exists
            if (!$this->envFileExists()) {
                $properly = false;
            }

            // Check if all database tables exists
            $namespace = 'App\\Models\\';
            $modelsPath = app_path('Models');
            $modelFiles = array_filter(\File::glob($modelsPath . '/' . '*.php'), 'is_file');

            if (count($modelFiles) > 0) {
                foreach ($modelFiles as $filePath) {
                    $filename = last(explode('/', $filePath));
                    $modelname = head(explode('.', $filename));

                    if (!str_contains(strtolower($filename), '.php') or str_contains(strtolower($modelname), 'base')) {
                        continue;
                    }

                    eval('$model = new ' . $namespace . $modelname . '();');
                    if (!\Schema::hasTable($model->getTable())) {
                        $properly = false;
                    }
                }
            }

            // Check Settings table
            if (Setting::count() <= 0) {
                $properly = false;
            }
            // Check TimeZone table
            if (TimeZone::count() <= 0) {
                $properly = false;
            }
        } catch (\PDOException $e) {
            $properly = false;
        } catch (\Exception $e) {
            $properly = false;
        }

        return $properly;
    }

    /**
     * Check if /.env file exists
     *
     * @return bool
     */
    public function envFileExists()
    {
        return file_exists(base_path('.env'));
    }

    /**
     * Get the script possible URL base (take to account installations in sub-folders)
	 *
     * @return mixed
     */
    private function getBaseUrl()
    {
        $currentUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . strtok($_SERVER["REQUEST_URI"],'?');
        $currentUrl = head(explode('/' . config('larapen.admin.route_prefix', 'admin'), $currentUrl));

        $baseUrl = head(explode('/install', $currentUrl));
        $baseUrl = rtrim($baseUrl, '/');

        return $baseUrl;
    }
	
	/**
	 * Check if an update is available
	 *
	 * @return bool
	 */
	private function checkUpdates()
	{
		$updateIsAvailable = false;
		
		// Get eventual new version value & the current (installed) version value
		$scriptVersionInt = strToInt(config('app.version'));
		$installedVersionInt = strToInt(getInstalledVersion());
		
		// Check the update
		if ($scriptVersionInt > $installedVersionInt) {
			$updateIsAvailable = true;
		}
		
		return $updateIsAvailable;
	}
}
