<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\File;
use Larapen\Admin\app\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\City;
use App\Models\SubAdmin1;
use App\Models\SubAdmin2;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AjaxController extends Controller
{
    /**
     * @param $table
     * @param $field
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveAjaxRequest($table, $field, Request $request)
    {
        $primaryKey = $request->input('primaryKey');
        $status = 0;
        $result = [
            'table'      => $table,
            'field'      => $field,
            'primaryKey' => $primaryKey,
            'status'     => $status,
        ];

        // Check parameters
        if (!Auth::check() || Auth::user()->is_admin != 1) {
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        if (!Schema::hasTable($table)) {
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        if (!Schema::hasColumn($table, $field)) {
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }
        $sql = 'SELECT DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = "' . DB::getTablePrefix() . $table . '" AND COLUMN_NAME = "' . $field . '"';
        $info = DB::select(DB::raw($sql));
        if (empty($info)) {
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        } else {
            if (isset($info[0]) && isset($info[0]->DATA_TYPE)) {
                if ($info[0]->DATA_TYPE != 'tinyint' && $table != 'settings') {
                    return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
                }
                if ($info[0]->DATA_TYPE != 'text' && $table == 'settings' && $field == 'value') {
                    return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
                }
            } else {
                return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
            }
        }


        // Get model namespace
        $namespace = '\\App\Models\\';

        // Get model name
        $model = null;
        $modelsPath = app_path('Models');
        $modelFiles = array_filter(\File::glob($modelsPath . '/' . '*.php'), 'is_file');
        if (count($modelFiles) > 0) {
            foreach ($modelFiles as $filePath) {
                $filename = last(explode('/', $filePath));
                $modelName = head(explode('.', $filename));

                if (!str_contains(strtolower($filename), '.php') || str_contains(strtolower($modelName), 'base')) {
                    continue;
                }

                eval('$modelChecker = new ' . $namespace . $modelName . '();');
                if (\Schema::hasTable($modelChecker->getTable())) {
                    if ($modelChecker->getTable() == $table) {
                        $model = $modelName;
                        break;
                    }
                }
            }
        }

        // Get table data
        $item = null;
        if (!empty($model)) {
            $model = $namespace . $model;
            $item = $model::find($primaryKey);
        }

        // Check item
        if (empty($item)) {
            return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
        }

        // UPDATE - the tinyint field

        // Geonames country data installation
        if ($table == 'countries' && $field == 'active') {
            if (strtolower(config('settings.app_default_country')) != strtolower($item->code)) {
                $resImport = false;
                if ($item->{$field} == 0) {
                    $resImport = $this->importGeonamesSql($item->code);
                } else {
                    $resImport = $this->removeGeonamesDataByCountryCode($item->code);
                }

                // Save data
                if ($resImport) {
                    $item->{$field} = ($item->{$field} == 0) ? 1 : 0;
                    $item->save();
                }

                $isDefaultCountry = 0;
            } else {
                $isDefaultCountry = 1;
                $resImport = true;
            }
        } else {
            // Save data
            $item->{$field} = ($item->{$field} == 0) ? 1 : 0;
            $item->save();

            // Set translations
            $this->updateTranslations($model, $item, $field, $item->{$field});
        }


        // JS data
        $result = [
            'table'      => $table,
            'field'      => $field,
            'primaryKey' => $primaryKey,
            'status'     => 1,
            'fieldValue' => $item->{$field},
        ];

        if (isset($isDefaultCountry)) {
            $result['isDefaultCountry'] = $isDefaultCountry;
        }
        if (isset($resImport)) {
            $result['resImport'] = $resImport;
        }


        return response()->json($result, 200, [], JSON_UNESCAPED_UNICODE);
    }


    /**
     * Import the Geonames data for the country
     *
     * @param $countryCode
     * @return bool
     */
    private function importGeonamesSql($countryCode)
    {
        // Remove all country data
        $this->removeGeonamesDataByCountryCode($countryCode);

		// Default country SQL file
		$srcPath = 'database/geonames/countries/';
		$tmpPath = 'app/' . $srcPath;
		$filename = strtolower($countryCode) . '.sql';
		$srcFilePath = storage_path($srcPath . $filename);
		$tmpFilePath = storage_path($tmpPath . $filename);

		// Check if rawFilePath exists
		if (!File::exists($srcFilePath)) {
			return false;
		}

		// Read and replace the database tables prefix
		$sql = File::get($srcFilePath);
		$sql = str_replace('<<prefix>>', DB::getTablePrefix(), $sql);

		// If NOT exists, create the tmp folders for SQL generation
		if (!File::isDirectory(storage_path($tmpPath))) {
			File::makeDirectory(storage_path($tmpPath), 0777, true, true);
		}

		// Create a new SQL file
		if (File::exists($tmpFilePath)) {
			File::delete($tmpFilePath);
		}
		File::put($tmpFilePath, $sql);

		// Try to import the SQL file
		try {
			// Temporary variable, used to store current query
			$tmpLine = '';
			// Read in entire file
			$lines = file($tmpFilePath);
			// Loop through each line
			foreach ($lines as $line) {
				// Skip it if it's a comment
				if (substr($line, 0, 2) == '--' || trim($line) == '') {
					continue;
				}

				// Add this line to the current segment
				$tmpLine .= $line;
				// If it has a semicolon at the end, it's the end of the query
				if (substr(trim($line), -1, 1) == ';') {
					// Perform the query
					DB::unprepared($tmpLine);
					// Reset temp variable to empty
					$tmpLine = '';
				}
			}
		} catch (\Exception $e) {
			$msg = 'Error when importing required data : ' . $e->getMessage();
			echo '<pre>'; print_r($msg); echo '</pre>';
			exit();
		}

		// Delete the SQL file
		if (File::exists($tmpFilePath)) {
			File::delete($tmpFilePath);
		}

		return true;
    }

    /**
     * Remove all the country's data
     *
     * @param $countryCode
     * @return bool
     */
    private function removeGeonamesDataByCountryCode($countryCode)
    {
        $deletedRows = SubAdmin1::countryOf($countryCode)->delete();
        $deletedRows = SubAdmin2::countryOf($countryCode)->delete();
        $deletedRows = City::countryOf($countryCode)->delete();

        // Delete all Posts entries
        $posts = Post::countryOf($countryCode)->get();
        if ($posts->count() > 0) {
            foreach ($posts as $post) {
                $post->delete();
            }
        }

        return true;
    }

    /**
     * Update translations entries - If model has translatable fields
     *
     * @param $model
     * @param $item
     * @param $field
     * @param $value
     */
    private function updateTranslations($model, $item, $field, $value)
    {
        if (property_exists($model, 'translatable')) {
            // If the entry is a default language entry, copy-paste its translations common data
            if ($item->id == $item->translation_of) {
                // ... AND don't select the current translated entry to prevent infinite recursion
                $entries = $model::where('id', '!=', $item->id)->where('translation_of', $item->translation_of)->get();

                // Copy-Paste for all languages
                if (!empty($entries)) {
                    foreach ($entries as $entry) {
                        $entry->{$field} = $value;
                        $entry->save();
                    }
                }
            }
        }
    }
}
