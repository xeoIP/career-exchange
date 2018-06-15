<?php

namespace App\Http\Controllers;

/*
 * Increase PHP page execution time for this controller.
 * NOTE: This function has no effect when PHP is running in safe mode (http://php.net/manual/en/ini.sect.safe-mode.php#ini.safe-mode).
 * There is no workaround other than turning off safe mode or changing the time limit (max_execution_time) in the php.ini.
 */
set_time_limit(0);

use App\Helpers\Curl;
use App\Helpers\Ip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Jenssegers\Date\Date;
use PulkitJalan\GeoIP\Facades\GeoIP;

class InstallController extends Controller
{
	public static $cookieExpiration = 3600;
	public $baseUrl;
	public $installUrl;

	/**
	 * InstallController constructor.
	 *
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		// From Laravel 5.3.4 or above
		$this->middleware(function ($request, $next) {
			$this->commonQueries($request);

			return $next($request);
		});

		// Create SQL destination path if not exists
		if (!File::exists(storage_path('app/database/geonames/countries'))) {
			File::makeDirectory(storage_path('app/database/geonames/countries'), 0755, true);
		}

		// Base URL
		$this->baseUrl = $this->getBaseUrl();
		view()->share('baseUrl', $this->baseUrl);
		config(['app.url' => $this->baseUrl]);

		// Installation URL
		$this->installUrl = $this->baseUrl . '/install';
		view()->share('installUrl', $this->installUrl);
	}

	/**
	 * Common Queries
	 *
	 * @param Request $request
	 */
	public function commonQueries(Request $request)
	{
		// Delete all front&back office sessions
		$request->session()->forget('country_code');
		$request->session()->forget('time_zone');
		$request->session()->forget('language_code');

		// Get country code by the user IP address
		$ipCountryCode = $this->getCountryCodeFromIPAddr();
	}

	/**
	 * Check for current step
	 *
	 * @param $request
	 * @param null $liveData
	 * @return int
	 */
	public function step($request, $liveData = null)
	{
		$step = 0;

		$data = $request->session()->get('compatibilities');
		if (isset($data)) {
			$step = 1;
		} else {
			return $step;
		}

		$data = $request->session()->get('site_info');
		if (isset($data)) {
			$step = 3;
		} else {
			return $step;
		}

		$data = $request->session()->get('database');
		if (isset($data)) {
			$step = 4;
		} else {
			return $step;
		}

		$data = $request->session()->get('database_imported');
		if (isset($data)) {
			$step = 5;
		} else {
			return $step;
		}

		$data = $request->session()->get('cron_jobs');
		if (isset($data)) {
			$step = 6;
		} else {
			return $step;
		}

		return $step;
	}

	/**
	 * STEP 0 - Starting installation
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function starting(Request $request)
	{
		$exitCode = Artisan::call('cache:clear');
		$exitCode = artisanConfigCache();
		$exitCode = Artisan::call('config:clear');

		return redirect($this->installUrl . '/system_compatibility');
	}

	/**
	 * STEP 1 - Check System Compatibility
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function systemCompatibility(Request $request)
	{
		// Begin check
		$request->session()->forget('compatibilities');

		// Check compatibilities
		$compatibilities = $this->checkSystemCompatibility();
		$result = true;
		foreach ($compatibilities as $compatibility) {
			if (!$compatibility['check']) {
				$result = false;
			}
		}

		// Retry if something not work yet
		try {
			if ($result) {
				$request->session()->put('compatibilities', $compatibilities);
			}

			return view('install.compatibilities', [
				'compatibilities' => $compatibilities,
				'result'          => $result,
				'step'            => $this->step($request),
				'current'         => 1,
			]);
		} catch (\Exception $e) {
			$exitCode = Artisan::call('cache:clear');
			$exitCode = artisanConfigCache();
			$exitCode = Artisan::call('config:clear');

			return redirect($this->installUrl . '/system_compatibility');
		}
	}

	/**
	 * STEP 2 - Set Site Info
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function siteInfo(Request $request)
	{
		if ($this->step($request) < 1) {
			return redirect($this->installUrl . '/system_compatibility');
		}

		// make sure session is working
		$rules = [
			'site_name'       => 'required',
			'site_slogan'     => 'required',
			'name'            => 'required',
			'purchase_code'   => 'required',
			'email'           => 'required|email',
			'password'        => 'required',
			'default_country' => 'required',
		];
		$smtp_rules = [
			'smtp_hostname'   => 'required',
			'smtp_port'       => 'required',
			'smtp_username'   => 'required',
			'smtp_password'   => 'required',
			'smtp_encryption' => 'required',
		];
		$mailgun_rules = [
			'mailgun_domain' => 'required',
			'mailgun_secret' => 'required',
		];
		$mandrill_rules = [
			'mandrill_secret' => 'required',
		];
		$ses_rules = [
			'ses_key'    => 'required',
			'ses_secret' => 'required',
			'ses_region' => 'required',
		];
		$sparkpost_rules = [
			'sparkpost_secret' => 'required',
		];

		// validate and save posted data
		if ($request->isMethod('post')) {
			$request->session()->forget('site_info');

			// Check purchase code
			$messages = [];
			//$purchase_code_data = $this->purchaseCodeChecker($request);
			$purchase_code_data = true;
			//if ($purchase_code_data->valid == false) {
			if ($purchase_code_data == false) {
				$rules['purchase_code_valid'] = 'required';
				if ($purchase_code_data->message != '') {
					$messages = ['purchase_code_valid.required' => 'The :attribute field is required. ERROR: <strong>' . $purchase_code_data->message . '</strong>'];
				}
			}

			if ($request->mail_driver == 'smtp') {
				$rules = array_merge($rules, $smtp_rules);
			}
			if ($request->mail_driver == 'mailgun') {
				$rules = array_merge($rules, $mailgun_rules);
			}
			if ($request->mail_driver == 'mandrill') {
				$rules = array_merge($rules, $mandrill_rules);
			}
			if ($request->mail_driver == 'ses') {
				$rules = array_merge($rules, $ses_rules);
			}
			if ($request->mail_driver == 'sparkpost') {
				$rules = array_merge($rules, $sparkpost_rules);
			}

			if (!empty($messages)) {
				$this->validate($request, $rules, $messages);
			} else {
				$this->validate($request, $rules);
			}

			// Check SMTP connection
			if ($request->mail_driver == 'smtp') {
				$rules = [];
				$messages = [];
				try {
					$transport = \Swift_SmtpTransport::newInstance($request->smtp_hostname, $request->smtp_port, $request->smtp_encryption);
					$transport->setUsername($request->smtp_username);
					$transport->setPassword($request->smtp_password);
					$mailer = \Swift_Mailer::newInstance($transport);
					$mailer->getTransport()->start();
				} catch (\Swift_TransportException $e) {
					$rules['smtp_valid'] = 'required';
					if ($e->getMessage() != '') {
						$messages = ['smtp_valid.required' => 'Can\'t connect to SMTP server. ERROR: <strong>' . $e->getMessage() . '</strong>'];
					}
				} catch (\Exception $e) {
					$rules['smtp_valid'] = 'required';
					if ($e->getMessage() != '') {
						$messages = ['smtp_valid.required' => 'Can\'t connect to SMTP server. ERROR: <strong>' . $e->getMessage() . '</strong>'];
					}
				}
				if (!empty($messages)) {
					$this->validate($request, $rules, $messages);
				} else {
					$this->validate($request, $rules);
				}
			}

			// Save data in session
			$siteInfo = $request->all();
			$request->session()->put('site_info', $siteInfo);

			return redirect($this->installUrl . '/database');
		}

		$siteInfo = $request->session()->get('site_info');
		if (!empty($request->old())) {
			$siteInfo = $request->old();
		}

		return view('install.site_info', [
			'site_info'       => $siteInfo,
			'rules'           => $rules,
			'smtp_rules'      => $smtp_rules,
			'mailgun_rules'   => $mailgun_rules,
			'mandrill_rules'  => $mandrill_rules,
			'ses_rules'       => $ses_rules,
			'sparkpost_rules' => $sparkpost_rules,
			'step'            => $this->step($request),
			'current'         => 2,
		]);
	}

	/**
	 * STEP 3 - Database configuration
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function database(Request $request)
	{
		if ($this->step($request) < 2) {
			return redirect($this->installUrl . '/site_info');
		}

		// Check required fields
		$rules = [
			'hostname'      => 'required',
			'port'          => 'required',
			'username'      => 'required',
			//'password'    => 'required', // Comment this line for local server
			'database_name' => 'required',
		];

		// Validate and save posted data
		if ($request->isMethod('post')) {
			$request->session()->forget('database');

			$this->validate($request, $rules);

			// Check mysql connection
			$messages = [];
			try {
				$port = $request->port;
				$port = (int)$port;
				$conn = new \mysqli($request->hostname, $request->username, $request->password, $request->database_name, $port, $request->socket);
			} catch (\Exception $e) {
				$rules['mysql_connection'] = 'required';
				if ($e->getMessage() != '') {
					$messages = ['mysql_connection.required' => 'Can\'t connect to MySQL server. ERROR: <strong>' . $e->getMessage() . '</strong>'];
				}
			}

			if (!empty($messages)) {
				$this->validate($request, $rules, $messages);
			} else {
				$this->validate($request, $rules);
			}

			// Get database info and Save it in session
			$database = $request->all();
			$request->session()->put('database', $database);

			// Write config file
			$this->writeEnv($request);

			// Return to Import Database page
			return redirect($this->installUrl . '/database_import');
		}

		$database = $request->session()->get('database');
		if (!empty($request->old())) {
			$database = $request->old();
		}

		return view('install.database', [
			'database' => $database,
			'rules'    => $rules,
			'step'     => $this->step($request),
			'current'  => 3,
		]);
	}

	/**
	 * STEP 4 - Import Database
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function databaseImport(Request $request)
	{
		if ($this->step($request) < 3) {
			return redirect($this->installUrl . '/database');
		}

		// Get database connexion info & site info
		$database = $request->session()->get('database');
		$siteInfo = $request->session()->get('site_info');

		if ($request->action == 'import') {
			$request->session()->forget('database_imported');

			// Get MySQLi resource
			$mysqli = $this->getMySQLiResource($database);

			// Check if database is not empty
			$rules = [];
			$tablesExist = false;
			$prefixCheck = empty($database['tables_prefix']) ? '' : "  AND table_name LIKE '" . $database['tables_prefix'] . "%'";
			$sql = "SELECT COUNT(DISTINCT `table_name`) as count
					FROM `information_schema`.`columns`
					WHERE `table_schema` = '" . $database['database_name'] . "'" . $prefixCheck;
			$result = $mysqli->query($sql);
			$result = $result->fetch_object();
			if ($result->count > 0) {
				$tablesExist = true;
			}

			// Drop all old table
			// 1. Get & Drop all table names, try 4 times
			$tableNames = [];
			$try = 5;
			while ($try > 0) {
				// extend query max setting
				$mysqli->query('FLUSH TABLES;');
				$mysqli->query('SET group_concat_max_len=9999999;');

				$tableNamesQuery = "SELECT GROUP_CONCAT(table_name) AS table_names
									FROM information_schema.tables
                                    WHERE table_schema = '" . $database['database_name'] . "'";
				$result = $mysqli->query($tableNamesQuery);
				$result = $result->fetch_object();
				$tableNames = array_merge($tableNames, explode(",", $result->table_names));

				// drop all tables
				$mysqli->query("SET FOREIGN_KEY_CHECKS = 0;");
				foreach ($tableNames as $table_name) {
					$mysqli->query("DROP TABLE $table_name;");
				}
				$mysqli->query("SET FOREIGN_KEY_CHECKS = 1;");

				$mysqli->query('FLUSH TABLES;');

				$try--;
			}

			// 2. Check if all table are dropped
			$canNotEmptyDatabase = false;
			foreach ($tableNames as $table_name) {
				$result = $mysqli->query("SELECT COUNT(*) FROM $table_name LIMIT 1;");

				// Table deleted if result == false
				if ($result) {
					$canNotEmptyDatabase = true;
				}
			}

			if ($canNotEmptyDatabase) {
				if ($tablesExist) {
					$rules['database_not_empty'] = 'required';
				}
				$rules["can_not_empty_database"] = "required";
			}

			// 3. Validation
			$this->validate($request, $rules);

			// Import database with prefix
			$this->importDatabase($database, $siteInfo);

			// The database is now imported !
			$request->session()->put('database_imported', true);

			$request->session()->flash('alert-success', trans('messages.install.database_import.success'));

			return redirect($this->installUrl . '/cron_jobs');
		}

		return view('install.database_import', [
			'database' => $database,
			'step'     => $this->step($request),
			'current'  => 3,
		]);
	}

	/**
	 * STEP 5 - Set Cron Jobs
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function cronJobs(Request $request)
	{
		if ($this->step($request) < 5) {
			return redirect($this->installUrl . '/database');
		}

		$request->session()->put('cron_jobs', true);

		return view('install.cron_jobs', [
			'step'    => $this->step($request),
			'current' => 5,
		]);
	}

	/**
	 * STEP 6 - Finish
	 *
	 * @param Request $request
	 * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function finish(Request $request)
	{
		if ($this->step($request) < 6) {
			return redirect($this->installUrl . '/database');
		}

		$request->session()->put('install_finish', true);

		// Delete all front&back office cookies
		if (isset($_COOKIE['ip_country_code'])) {
			unset($_COOKIE['ip_country_code']);
		}

		// Clear all Cache
		$exitCode = Artisan::call('cache:clear');
		sleep(2);
		$exitCode = Artisan::call('view:clear');
		sleep(1);
		File::delete(File::glob(storage_path('logs') . '/laravel*.log'));

		// Rendering final Info
		return view('install.finish', [
			'step'    => $this->step($request),
			'current' => 6,
		]);
	}


	/**
	 * Check for requirement when install app
	 *
	 * @return array
	 */
	private function checkSystemCompatibility()
	{
		// Fix unknown public folder (For 'public/uploads' folder)
		$userPublicFolder = last(explode(DIRECTORY_SEPARATOR, public_path()));

		return [
			[
				'type'  => 'requirement',
				'name'  => 'PHP version',
				'check' => version_compare(PHP_VERSION, '5.6.4', '>='),
				'note'  => 'PHP 5.6.4 or higher is required.',
			],
			[
				'type'  => 'requirement',
				'name'  => 'OpenSSL Extension',
				'check' => extension_loaded('openssl'),
				'note'  => 'OpenSSL PHP Extension is required.',
			],
			[
				'type'  => 'requirement',
				'name'  => 'Mbstring PHP Extension',
				'check' => extension_loaded('mbstring'),
				'note'  => 'Mbstring PHP Extension is required.',
			],
			[
				'type'  => 'requirement',
				'name'  => 'PDO PHP Extension',
				'check' => extension_loaded('pdo'),
				'note'  => 'PDO PHP Extension is required.',
			],
			[
				'type'  => 'requirement',
				'name'  => 'Tokenizer PHP Extension',
				'check' => extension_loaded('tokenizer'),
				'note'  => 'Tokenizer PHP Extension is required.',
			],
			[
				'type'  => 'requirement',
				'name'  => 'XML PHP Extension',
				'check' => extension_loaded('xml'),
				'note'  => 'XML PHP Extension is required.',
			],
			[
				'type'  => 'requirement',
				'name'  => 'PHP Fileinfo Extension',
				'check' => extension_loaded('fileinfo'),
				'note'  => 'PHP Fileinfo Extension is required.',
			],
			[
				'type'  => 'requirement',
				'name'  => 'PHP exec() Function',
				'check' => exec_enabled(),
				'note'  => 'PHP Exec Function is required.',
			],
			[
				'type'  => 'requirement',
				'name'  => 'PHP GD Library',
				'check' => (extension_loaded('gd') && function_exists('gd_info')),
				'note'  => 'PHP GD Library is required.',
			],
			[
				'type'  => 'permission',
				'name'  => 'bootstrap/cache/',
				'check' => file_exists(base_path('/bootstrap/cache')) &&
					is_dir(base_path('/bootstrap/cache')) &&
					(is_writable(base_path('/bootstrap/cache'))) &&
					getPerms(base_path('/bootstrap/cache')) >= 775,
				'note'  => 'The directory must be writable by the web server (0775).',
			],
			[
				'type'  => 'permission',
				'name'  => $userPublicFolder . '/uploads/',
				'check' => file_exists(base_path('/' . $userPublicFolder . '/uploads')) &&
					is_dir(base_path('/' . $userPublicFolder . '/uploads')) &&
					(is_writable(base_path('/' . $userPublicFolder . '/uploads'))) &&
					getPerms(base_path('/' . $userPublicFolder . '/uploads')) >= 775,
				'note'  => 'The directory must be writable by the web server (0775).',
			],
			[
				'type'  => 'permission',
				'name'  => 'storage/',
				'check' => (file_exists(base_path('/storage')) &&
						is_dir(base_path('/storage')) &&
						(is_writable(base_path('/storage'))) &&
						getPerms(base_path('/storage')) >= 775),
				'note'  => 'The directory must be writable (recursively) by the web server (0775).',
			],
		];
	}

	/**
	 * @return string
	 */
	public function checkServerVar()
	{
		$vars = ['HTTP_HOST', 'SERVER_NAME', 'SERVER_PORT', 'SCRIPT_NAME', 'SCRIPT_FILENAME', 'PHP_SELF', 'HTTP_ACCEPT', 'HTTP_USER_AGENT'];
		$missing = [];
		foreach ($vars as $var) {
			if (!isset($_SERVER[$var])) {
				$missing[] = $var;
			}
		}

		if (!empty($missing)) {
			return '$_SERVER does not have: ' . implode(', ', $missing);
		}

		if (!isset($_SERVER['REQUEST_URI']) && isset($_SERVER['QUERY_STRING'])) {
			return 'Either $_SERVER["REQUEST_URI"] or $_SERVER["QUERY_STRING"] must exist.';
		}

		if (!isset($_SERVER['PATH_INFO']) && strpos($_SERVER['PHP_SELF'], $_SERVER['SCRIPT_NAME']) !== 0) {
			return 'Unable to determine URL path info. Please make sure $_SERVER["PATH_INFO"] (or $_SERVER["PHP_SELF"] and $_SERVER["SCRIPT_NAME"]) contains proper value.';
		}

		return '';
	}

	/**
	 * Write configuration values to file
	 *
	 * @param $request
	 */
	private function writeEnv($request)
	{
		// Get .env file path
		$envFilePath = base_path('.env');

		// Set app key
		$key = 'base64:' . base64_encode($this->randomString(32));
		$key = config('app.key', $key);

		// Get app url & host
		$appHost = getHostByUrl($this->baseUrl);

		// Get app version
		$version = config('app.version');

		// Get database info
		$database = $request->session()->get('database');

		// Generate .env file string
		$configString = 'APP_ENV=production
APP_KEY=' . $key . '
APP_DEBUG=false
APP_URL=' . $this->baseUrl . '
FORCE_HTTPS=false
APP_VERSION=' . $version . '

DB_HOST=' . (isset($database['hostname']) ? $database['hostname'] : '') . '
DB_PORT=' . (isset($database['port']) ? $database['port'] : '') . '
DB_DATABASE=' . (isset($database['database_name']) ? $database['database_name'] : '') . '
DB_USERNAME=' . (isset($database['username']) ? $database['username'] : '') . '
DB_PASSWORD=' . (isset($database['password']) ? $database['password'] : '') . '
DB_SOCKET=' . (isset($database['socket']) ? $database['socket'] : '') . '
DB_TABLES_PREFIX=' . (isset($database['tables_prefix']) ? $database['tables_prefix'] : '') . '
DB_CHARSET=utf8
DB_COLLATION=utf8_unicode_ci
DB_DUMP_COMMAND_PATH=

IMAGE_DRIVER=gd

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

APP_LOG_LEVEL=debug
APP_LOG=daily
APP_LOG_MAX_FILES=2

FORM_REGISTER_HIDE_PHONE=false
FORM_REGISTER_HIDE_EMAIL=false
FORM_REGISTER_HIDE_USERNAME=true

PAYPAL_MODE=
PAYPAL_USERNAME=
PAYPAL_PASSWORD=
PAYPAL_SIGNATURE=
';
		// Save the new .env file
		$this->writeFile($envFilePath, $configString);

		// Reload .env (related to the config values)
		$exitCode = artisanConfigCache();
		$exitCode = Artisan::call('config:clear');
	}

	/**
	 * Import Database - Migration & Seed
	 *
	 * @param $database
	 * @param $siteInfo
	 * @return bool
	 */
	private function importDatabase($database, $siteInfo)
	{
		// Import database schema
		$this->importSchemaSql($database);

		// Import required data
		$this->importRequiredDataSql($database);

		// Import Geonames Default country database
		$this->importGeonamesSql($database, $siteInfo);

		// Update database with customer info
		$this->updateDatabase($database, $siteInfo);

		return true;
	}

	/**
	 * Import Database Schema
	 *
	 * @param $database
	 * @return bool
	 */
	private function importSchemaSql($database)
	{
		// Default country SQL file
		$filename = 'database/schema.sql';
		$rawFilePath = storage_path($filename);
		$filePath = storage_path('app/' . $filename);
		$this->setSqlWithDbPrefix($rawFilePath, $filePath, $database);

		return $this->importSql($database, $filePath);
	}

	/**
	 * Import required data
	 *
	 * @param $database
	 * @return bool
	 */
	private function importRequiredDataSql($database)
	{
		// Default country SQL file
		$filename = 'database/data.sql';
		$rawFilePath = storage_path($filename);
		$filePath = storage_path('app/' . $filename);
		$this->setSqlWithDbPrefix($rawFilePath, $filePath, $database);

		return $this->importSql($database, $filePath);
	}

	/**
	 * Import Geonames Default country database
	 *
	 * @param $database
	 * @param $siteInfo
	 * @return bool
	 */
	private function importGeonamesSql($database, $siteInfo)
	{
		if (!isset($siteInfo['default_country'])) return false;

		// Default country SQL file
		$filename = 'database/geonames/countries/' . strtolower($siteInfo['default_country']) . '.sql';
		$rawFilePath = storage_path($filename);
		$filePath = storage_path('app/' . $filename);

		$this->setSqlWithDbPrefix($rawFilePath, $filePath, $database);

		return $this->importSql($database, $filePath);
	}

	/**
	 * @param $database
	 * @param $filePath
	 * @return bool
	 */
	private function importSql($database, $filePath)
	{
		// Get MySQLi resource
		$mysqli = $this->getMySQLiResource($database, true);

		try {
			$errorDetect = false;

			// Temporary variable, used to store current query
			$tmpLine = '';
			// Read in entire file
			$lines = file($filePath);
			// Loop through each line
			foreach ($lines as $line) {
				// Skip it if it's a comment
				if (substr($line, 0, 2) == '--' || trim($line) == '') {
					continue;
				}
				if (substr($line, 0, 2) == '/*') {
					//continue;
				}

				// Add this line to the current segment
				$tmpLine .= $line;
				// If it has a semicolon at the end, it's the end of the query
				if (substr(trim($line), -1, 1) == ';') {
					// Perform the query
					if (!$mysqli->query($tmpLine)) {
						echo "<pre>Error performing query '<strong>" . $tmpLine . "</strong>' : " . $mysqli->error . " - Code: " . $mysqli->errno . "</pre><br />";
						$errorDetect = true;
					}
					// Reset temp variable to empty
					$tmpLine = '';
				}
			}
			// Check if error is detected
			if ($errorDetect) {
				dd('ERROR');
			}
		} catch (\Exception $e) {
			$msg = 'Error when importing required data : ' . $e->getMessage();
			echo '<pre>';
			print_r($msg);
			echo '</pre>';
			exit();
		}

		// Close MySQL connexion
		$mysqli->close();

		// Delete the SQL file
		if (file_exists($filePath)) {
			unlink($filePath);
		}

		return true;
	}

	/**
	 * @param $database
	 * @param $siteInfo
	 */
	private function updateDatabase($database, $siteInfo)
	{
		// Get MySQLi resource
		$mysqli = $this->getMySQLiResource($database, true);

		// Default date
		$date = Date::now();


		// USERS - Insert default superuser
		$mysqli->query('DELETE FROM `' . $database['tables_prefix'] . 'users` WHERE 1');
		$sql = 'INSERT INTO `' . $database['tables_prefix'] . "users`
            (`id`, `country_code`, `user_type_id`, `gender_id`, `name`, `about`, `email`, `password`, `is_admin`, `verified_email`, `verified_phone`)
            VALUES (1, '" . $siteInfo['default_country'] . "', 1, 1, '" . $siteInfo['name'] . "', 'Administrator', '" . $siteInfo['email'] . "', '" . bcrypt($siteInfo['password']) . "', 1, 1, 1);";
		$aaa = $mysqli->query($sql);


		// COUNTRIES - Activate default country
		$sql = 'UPDATE `' . $database['tables_prefix'] . 'countries` SET `active`=1 WHERE `code`="' . $siteInfo['default_country'] . '"';
		$mysqli->query($sql);


		// SETTINGS - Update settings
		$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['purchase_code'] . '" WHERE `key`="purchase_code"';
		$mysqli->query($sql);
		$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['site_name'] . '" WHERE `key`="app_name"';
		$mysqli->query($sql);
		$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['site_slogan'] . '" WHERE `key`="app_slogan"';
		$mysqli->query($sql);
		$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['email'] . '" WHERE `key`="app_email"';
		$mysqli->query($sql);
		$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['email'] . '" WHERE `key`="app_email_sender"';
		$mysqli->query($sql);
		$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['default_country'] . '" WHERE `key`="app_default_country"';
		$mysqli->query($sql);
		$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['mail_driver'] . '" WHERE `key`="mail_driver"';
		$mysqli->query($sql);
		if (in_array($siteInfo['mail_driver'], ['smtp', 'mailgun', 'mandrill', 'ses', 'sparkpost'])) {
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['smtp_hostname'] . '" WHERE `key`="mail_host"';
			$mysqli->query($sql);
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['smtp_port'] . '" WHERE `key`="mail_port"';
			$mysqli->query($sql);
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['smtp_encryption'] . '" WHERE `key`="mail_encryption"';
			$mysqli->query($sql);
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['smtp_username'] . '" WHERE `key`="mail_username"';
			$mysqli->query($sql);
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['smtp_password'] . '" WHERE `key`="mail_password"';
			$mysqli->query($sql);
		}
		if ($siteInfo['mail_driver'] == 'mailgun') {
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['mailgun_domain'] . '" WHERE `key`="mailgun_domain"';
			$mysqli->query($sql);
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['mailgun_secret'] . '" WHERE `key`="mailgun_secret"';
			$mysqli->query($sql);
		}
		if ($siteInfo['mail_driver'] == 'mandrill') {
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['mandrill_secret'] . '" WHERE `key`="mandrill_secret"';
			$mysqli->query($sql);
		}
		if ($siteInfo['mail_driver'] == 'ses') {
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['ses_key'] . '" WHERE `key`="ses_key"';
			$mysqli->query($sql);
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['ses_secret'] . '" WHERE `key`="ses_secret"';
			$mysqli->query($sql);
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['ses_region'] . '" WHERE `key`="ses_region"';
			$mysqli->query($sql);
		}
		if ($siteInfo['mail_driver'] == 'sparkpost') {
			$sql = 'UPDATE `' . $database['tables_prefix'] . 'settings` SET `value`="' . $siteInfo['sparkpost_secret'] . '" WHERE `key`="sparkpost_secret"';
			$mysqli->query($sql);
		}
	}

	/**
	 * @param $rawFilePath
	 * @param $filePath
	 * @param $database
	 * @return mixed|string
	 */
	private function setSqlWithDbPrefix($rawFilePath, $filePath, $database)
	{
		if (!file_exists($rawFilePath)) {
			return '';
		}

		// Read and replace prefix
		$sql = $this->getFile($rawFilePath);
		$sql = str_replace('<<prefix>>', $database['tables_prefix'], $sql);

		// Write file
		$this->writeFile($filePath, $sql);

		return $sql;
	}

	/**
	 * Get MySQLi resource
	 *
	 * @param $database
	 * @param bool $utf8
	 * @return \mysqli
	 */
	private function getMySQLiResource($database, $utf8 = false)
	{
		// MySQL parameters
		$mysql_host = isset($database['hostname']) ? $database['hostname'] : '';
		$mysql_port = isset($database['port']) ? $database['port'] : '';
		$mysql_username = isset($database['username']) ? $database['username'] : '';
		$mysql_password = isset($database['password']) ? $database['password'] : '';
		$mysql_database = isset($database['database_name']) ? $database['database_name'] : '';
		$mysql_socket = isset($database['socket']) ? $database['socket'] : '';

		// Connect to MySQL server
		$mysqli = new \mysqli($mysql_host, $mysql_username, $mysql_password, $mysql_database, $mysql_port, $mysql_socket);

		// Check connection
		if ($mysqli->connect_errno) {
			printf("Connect failed: %s\n", $mysqli->connect_error);
			exit();
		}

		// Change character set to utf8
		if ($utf8 and !$mysqli->set_charset('utf8')) {
			printf("Error loading character set utf8: %s\n", $mysqli->error);
			exit();
		}

		return $mysqli;
	}

	/**
	 * @param $filename
	 * @return string
	 */
	private function getFile($filename)
	{
		$file = fopen($filename, 'r') or die('Unable to open file!');
		$buffer = fread($file, filesize($filename));
		fclose($file);

		return $buffer;
	}

	/**
	 * @param $filename
	 * @param $buffer
	 */
	private function writeFile($filename, $buffer)
	{
		// Delete the file before writing
		if (file_exists($filename)) {
			unlink($filename);
		}

		// Write the new file
		$file = fopen($filename, 'w') or die('Unable to open file!');
		fwrite($file, $buffer);
		fclose($file);
	}

	/**
	 * @return bool|string
	 */
	private static function getCountryCodeFromIPAddr()
	{
		if (isset($_COOKIE['ip_country_code'])) {
			$countryCode = $_COOKIE['ip_country_code'];
		} else {
			// Localize the user's country
			try {
				$ipAddr = Ip::get();

				GeoIP::setIp($ipAddr);
				$countryCode = GeoIP::getCountryCode();

				if (!is_string($countryCode) or strlen($countryCode) != 2) {
					return null;
				}
			} catch (\Exception $e) {
				return null;
			}

			// Set data in cookie
			if (isset($_COOKIE['ip_country_code'])) {
				unset($_COOKIE['ip_country_code']);
			}
			setcookie('ip_country_code', $countryCode);
		}

		return $countryCode;
	}

	/**
	 * @param Request $request
	 * @return mixed|string
	 */
	private function purchaseCodeChecker(Request $request)
	{
		$apiUrl = config('larapen.core.purchase_code_checker_url') . $request->purchase_code . '&item_id=' . config('larapen.core.item_id');
		$data = Curl::fetch($apiUrl);

		// Check & Get cURL error by checking if 'data' is a valid json
		if (!isValidJson($data)) {
			$data = json_encode(['valid' => false, 'message' => 'Invalid purchase code. ' . strip_tags($data)]);
		}

		// Format object data
		$data = json_decode($data);

		// Check if 'data' has the valid json attributes
		if (!isset($data->valid) || !isset($data->message)) {
			$data = json_encode(['valid' => false, 'message' => 'Invalid purchase code. Incorrect data format.']);
			$data = json_decode($data);
		}

		return $data;
	}

	/**
	 * Get the script possible URL base (take to account installations in sub-folders)
	 *
	 * @return mixed
	 */
	private function getBaseUrl()
	{
		$currentUrl = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . strtok($_SERVER["REQUEST_URI"], '?');
		$currentUrl = head(explode('/' . config('larapen.admin.route_prefix', 'admin'), $currentUrl));

		$baseUrl = head(explode('/install', $currentUrl));
		$baseUrl = rtrim($baseUrl, '/');

		return $baseUrl;
	}

	/**
	 * @param int $length
	 * @return string
	 */
	private function randomString($length = 6)
	{
		$str = "";
		$characters = array_merge(range('A', 'Z'), range('a', 'z'), range('0', '9'));
		$max = count($characters) - 1;
		for ($i = 0; $i < $length; $i++) {
			$rand = mt_rand(0, $max);
			$str .= $characters[$rand];
		}

		return $str;
	}
}
