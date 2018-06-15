<?php

namespace App\Providers;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;

class AppServiceProvider extends ServiceProvider
{
    private $cacheExpiration = 1440; // Cache for 1 day (60 * 24)

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force HTTPS protocol
        $this->forceHttps();

        // Create setting config var for the default language
        $this->getDefaultLanguage();

        // Create config vars from settings table
        $this->createConfigVars();

        // Update the config vars
        $this->setConfigVars();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    // Force HTTPS protocol
    private function forceHttps()
    {
        if (config('larapen.core.force_https') == true) {
            URL::forceScheme('https');
        }
    }

    // Create setting config var for the default language
    private function getDefaultLanguage()
    {
        try {
            // Get the DB default language
            $defaultLang = Cache::remember('language.default', $this->cacheExpiration, function () {
                $defaultLang = Language::where('default', 1)->first();
                return $defaultLang;
            });

            if (!empty($defaultLang)) {
                Config::set('applang', $defaultLang->toArray());
            } else {
                Config::set('applang.abbr', config('app.locale'));
            }
        } catch (\Exception $e) {
            Config::set('applang.abbr', config('app.locale'));
        }
    }

    // Create config vars from settings table
    private function createConfigVars()
    {
        // Check DB connection and catch it
        try {
            // Get all settings from the database
            $settings = Cache::remember('settings.active', $this->cacheExpiration, function () {
                $settings = Setting::where('active', 1)->get();
                return $settings;
            });

            // Bind all settings to the Laravel config, so you can call them like
            if ($settings->count() > 0) {
                foreach ($settings as $key => $setting) {
                    if (!empty($setting->value)) {
                        Config::set('settings.' . $setting->key, $setting->value);
                    }
                }
            }
        } catch (\Exception $e) {
            Config::set('settings.error', true);
            Config::set('settings.app_logo', 'app/default/logo.png');
        }
    }

    // Update the config vars
    private function setConfigVars()
    {
        // App name
        Config::set('app.name', config('settings.app_name'));
        // reCAPTCHA
        Config::set('recaptcha.public_key', env('RECAPTCHA_PUBLIC_KEY', config('settings.recaptcha_public_key')));
        Config::set('recaptcha.private_key', env('RECAPTCHA_PRIVATE_KEY', config('settings.recaptcha_private_key')));
        // Mail
        Config::set('mail.driver', env('MAIL_DRIVER', config('settings.mail_driver')));
        Config::set('mail.host', env('MAIL_HOST', config('settings.mail_host')));
        Config::set('mail.port', env('MAIL_PORT', config('settings.mail_port')));
        Config::set('mail.encryption', env('MAIL_ENCRYPTION', config('settings.mail_encryption')));
        Config::set('mail.username', env('MAIL_USERNAME', config('settings.mail_username')));
        Config::set('mail.password', env('MAIL_PASSWORD', config('settings.mail_password')));
        Config::set('mail.from.address', env('MAIL_FROM_ADDRESS', config('settings.app_email_sender')));
        Config::set('mail.from.name', env('MAIL_FROM_NAME', config('settings.app_name')));
        // Mailgun
        Config::set('services.mailgun.domain', env('MAILGUN_DOMAIN', config('settings.mailgun_domain')));
        Config::set('services.mailgun.secret', env('MAILGUN_SECRET', config('settings.mailgun_secret')));
        // Mandrill
        Config::set('services.mandrill.secret', env('MANDRILL_SECRET', config('settings.mandrill_secret')));
        // Amazon SES
        Config::set('services.ses.key', env('SES_KEY', config('settings.ses_key')));
        Config::set('services.ses.secret', env('SES_SECRET', config('settings.ses_secret')));
        Config::set('services.ses.region', env('SES_REGION', config('settings.ses_region')));
        // Sparkpost
        Config::set('services.sparkpost.secret', env('SPARKPOST_SECRET', config('settings.sparkpost_secret')));
        // Facebook
        Config::set('services.facebook.client_id', env('FACEBOOK_CLIENT_ID', config('settings.facebook_client_id')));
        Config::set('services.facebook.client_secret', env('FACEBOOK_CLIENT_SECRET', config('settings.facebook_client_secret')));
        // Google
        Config::set('services.google.client_id', env('GOOGLE_CLIENT_ID', config('settings.google_client_id')));
        Config::set('services.google.client_secret', env('GOOGLE_CLIENT_SECRET', config('settings.google_client_secret')));
        Config::set('services.googlemaps.key', env('GOOGLE_MAPS_API_KEY', config('settings.googlemaps_key')));
        // Meta-tags
        Config::set('meta-tags.title', config('settings.app_slogan'));
        Config::set('meta-tags.open_graph.site_name', config('settings.app_name'));
        Config::set('meta-tags.twitter.creator', config('settings.twitter_username'));
        Config::set('meta-tags.twitter.site', config('settings.twitter_username'));

        // Fix unknown public folder (for elFinder)
        Config::set('elfinder.roots.0.path', public_path('uploads'));

        // Use DB checkbox field Settings (for Admin panel)
        if (str_contains(config('settings.show_powered_by'), 'fa')) {
            Config::set('larapen.admin.show_powered_by', str_contains(config('settings.show_powered_by'), 'fa-check-square-o') ? 1 : 0);
        } else {
            Config::set('larapen.admin.show_powered_by', config('settings.show_powered_by'));
        }

        // Admin panel theme
        config(['larapen.admin.skin' => config('settings.admin_skin')]);
    }
}
