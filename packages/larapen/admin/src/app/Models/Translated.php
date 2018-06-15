<?php

namespace Larapen\Admin\app\Models;

use Illuminate\Support\Facades\Request;

trait Translated
{
    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getTranslationOfAttribute()
    {
        $translationOf = (isset($this->attributes['translation_of'])) ? $this->attributes['translation_of'] : null;
        $entityId = (isset($this->attributes['id'])) ? $this->attributes['id'] : $translationOf;

        // Admin panel
        if (Request::segment(1) == config('larapen.admin.route_prefix', 'admin')) {
            return $translationOf;
        }

        // FrontOffice
        if (!empty($translationOf)) {
            if ($this->attributes['translation_lang'] == config('applang.abbr')) {
                return $entityId;
            } else {
                return $translationOf;
            }
        } else {
            return $entityId;
        }
    }

    public function getTidAttribute()
    {
        $translationOf = (isset($this->attributes['translation_of'])) ? $this->attributes['translation_of'] : null;
        $entityId = (isset($this->attributes['id'])) ? $this->attributes['id'] : $translationOf;

        // Admin panel
        if (Request::segment(1) == config('larapen.admin.route_prefix', 'admin')) {
            return $translationOf;
        }

        // FrontOffice
        if (!empty($translationOf)) {
            if ($this->attributes['translation_lang'] == config('applang.abbr')) {
                return $entityId;
            } else {
                return $translationOf;
            }
        } else {
            return $entityId;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
    public function setTranslationOfAttribute($value)
    {
        $entityId = (isset($this->attributes['id'])) ? $this->attributes['id'] : null;

        if (empty($value)) {
            if ($this->attributes['translation_lang'] == config('applang.abbr')) {
                $this->attributes['translation_of'] = $entityId;
            } else {
                $this->attributes['translation_of'] = $value;
            }
        } else {
            $this->attributes['translation_of'] = $value;
        }
    }
}