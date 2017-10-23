<?php

/**
 * Default Admin translator (e.g. admin::messages.php)
 *
 * @param $string
 * @param array $params
 * @param string $file
 * @param null $locale
 * @return string|\Symfony\Component\Translation\TranslatorInterface
 */
function __t($string, $params = [], $file = 'admin::messages', $locale = null)
{
    if (is_null($locale)) {
        $locale = config('app.locale');
    }

    return trans($file . '.' . $string, $params, $locale);
}

/**
 * Checkbox Display
 *
 * @param $fieldValue
 * @return string
 */
function checkboxDisplay($fieldValue)
{
    if ($fieldValue == 1) {
        return '<i class="fa fa-check-square-o" aria-hidden="true"></i>';
    } else {
        return '<i class="fa fa-square-o" aria-hidden="true"></i>';
    }
}

/**
 * Ajax Checkbox Display
 *
 * @param $id
 * @param $table
 * @param $field
 * @param null $fieldValue
 * @return string
 */
function ajaxCheckboxDisplay($id, $table, $field, $fieldValue = null)
{
    $lineId = $field.$id;
    $lineId = str_replace('.', '', $lineId); // fix JS bug (in admin layout)
    $data = 'data-table="' . $table . '"
			data-field="'.$field.'"
			data-line-id="' . $lineId . '"
			data-id="' . $id . '"
			data-value="' . (isset($fieldValue) ? $fieldValue : 0) . '"';

    // Decoration
    if (isset($fieldValue) && $fieldValue == 1) {
        $html = '<i id="' . $lineId . '" class="fa fa-check-square-o" aria-hidden="true"></i>';
    } else {
        $html = '<i id="' . $lineId . '" class="fa fa-square-o" aria-hidden="true"></i>';
    }
    $html = '<a href="" class="ajax-request" ' . $data . '>' . $html . '</a>';

    return $html;
}

/**
 * Advanced Ajax Checkbox Display
 *
 * @param $id
 * @param $table
 * @param $field
 * @param null $fieldValue
 * @return string
 */
function installAjaxCheckboxDisplay($id, $table, $field, $fieldValue = null)
{
    $lineId = $field.$id;
    $lineId = str_replace('.', '', $lineId); // fix JS bug (in admin layout)
    $data = 'data-table="' . $table . '"
			data-field="'.$field.'"
			data-line-id="' . $lineId . '"
			data-id="' . $id . '"
			data-value="' . $fieldValue . '"';

    // Decoration
    if ($fieldValue == 1) {
        $html = '<i id="' . $lineId . '" class="fa fa-check-square-o" aria-hidden="true"></i>';
    } else {
        $html = '<i id="' . $lineId . '" class="fa fa-square-o" aria-hidden="true"></i>';
    }
    $html = '<a href="" class="ajax-request" ' . $data . '>' . $html . '</a>';

    // Install country's decoration
    $html .= ' - ';
    if ($fieldValue == 1) {
        $html .= '<a href="" id="install' . $id . '" class="ajax-request btn btn-xs btn-success" ' . $data . '><i class="fa fa-download"></i> ' . __t('Installed') . '</a>';
    } else {
        $html .= '<a href="" id="install' . $id . '" class="ajax-request btn btn-xs btn-default" ' . $data . '><i class="fa fa-download"></i> ' . __t('Install') . '</a>';
    }

    return $html;
}

/**
 * Generate the Post's link from the Admin panel
 *
 * @param $post
 * @return string
 */
function getPostUrl($post)
{
    $out = '';

    // Get Cache Expiration Time
    $cacheExpiration = (int)config('settings.app_cache_expiration');

    // Get payment Info
    $cacheId = 'admin.getPostUrl.payment.' . $post->id;
    $payment = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($post) {
        $payment = \App\Models\Payment::where('post_id', $post->id)->orderBy('id', 'DESC')->first();
        return $payment;
    });

    if (!empty($payment)) {
        // Get Pack Info
        $cacheId = 'admin.getPostUrl.package.' . $payment->package_id . '.' . config('app.locale');
        $package = \Illuminate\Support\Facades\Cache::remember($cacheId, $cacheExpiration, function () use ($payment) {
            $package = \App\Models\Package::transById($payment->package_id);
            return $package;
        });

        if (!empty($package)) {
            if ($post->featured == 1) {
                $class = 'text-success';
                $info = '';
            } else {
                $class = 'text-danger';
                $info = ' (' . __t('Expired') . ')';
            }
            $out = ' <i class="fa fa-check-circle ' . $class . ' tooltipHere"
                    title="" data-placement="bottom" data-toggle="tooltip"
                    type="button" data-original-title="' . $package->short_name . $info . '">
                </i>';
        }
    }

    // Get preview possibility
    $preview = !isVerifiedPost($post) ? '?preview=1' : '';

    // Get URL
    $url = url(config('app.locale') . '/' . slugify($post->title) . '/' . $post->id . '.html') . $preview;
    $out = '<a href="' . $url . '" target="_blank">' . str_limit($post->title, 60) . '</a>' . $out;

    return $out;
}

/**
 * Check if the Post is verified
 *
 * @param $post
 * @return bool
 */
function isVerifiedPost($post)
{
    if (!isset($post->verified_email) || !isset($post->verified_phone) || !isset($post->reviewed)) {
        return false;
    }

    if (config('settings.posts_review_activation')) {
        $verified = ($post->verified_email == 1 && $post->verified_phone == 1 && $post->reviewed == 1) ? true : false;
    } else {
        $verified = ($post->verified_email == 1 && $post->verified_phone == 1) ? true : false;
    }

    return $verified;
}

/**
 * Check if the User is verified
 *
 * @param $user
 * @return bool
 */
function isVerifiedUser($user)
{
    if (!isset($user->verified_email) || !isset($user->verified_phone)) {
        return false;
    }

    $verified = ($user->verified_email == 1 && $user->verified_phone == 1) ? true : false;

    return $verified;
}
