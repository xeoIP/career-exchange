<?php

namespace App\Http\Middleware;

use Closure;
use Mews\Purifier\Facades\Purifier;

class TransformInput
{
	/**
	 * @param $request
	 * @param Closure $next
	 * @return mixed
	 */
    public function handle($request, Closure $next)
    {
        if (in_array(strtolower($request->method()), ['post', 'put', 'patch'])) {
            $this->proccessBeforeValidation($request);
        }
        
        return $next($request);
    }

	/**
	 * @param $request
	 */
    public function proccessBeforeValidation($request)
    {
        $input = $request->all();

        // description
        if ($request->filled('description')) {
            if (config('settings.simditor_wysiwyg') || config('settings.ckeditor_wysiwyg')) {
                $input['description'] = Purifier::clean($request->input('description'));
            } else {
                $input['description'] = str_clean($request->input('description'));
            }
        }

        // salary_min
        if ($request->filled('salary_min')) {
            $input['salary_min'] = str_replace(',', '.', $request->input('salary_min'));
            $input['salary_min'] = preg_replace('/[^0-9\.]/', '', $input['salary_min']);
        }

        // salary_max
		if ($request->filled('salary_max')) {
            $input['salary_max'] = str_replace(',', '.', $request->input('salary_max'));
			$input['salary_max'] = preg_replace('/[^0-9\.]/', '', $input['salary_max']);
		}
	
		// phone
		if ($request->filled('phone')) {
			$input['phone'] = phoneFormatInt($request->input('phone'), $request->input('country', session('country_code')));
		}
	
		// login (phone)
		if ($request->filled('login')) {
			$loginField = getLoginField($request->input('login'));
			if ($loginField == 'phone') {
				$input['login'] = phoneFormatInt($request->input('login'), $request->input('country', session('country_code')));
			}
		}
        
        $request->replace($input);
    }
}
