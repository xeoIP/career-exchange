<?php

namespace App\Models\Traits;


trait TranslatedTrait
{
    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public static function transById($id, $locale = '')
    {
        if (empty($locale) || $locale == '') {
            $locale = config('app.locale');
        }

        $entry = static::where('translation_of', $id)->where('translation_lang', $locale)->first();

        if (empty($entry)) {
            $entry = static::find($id);
        }

        return $entry;
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function translated()
    {
        return $this->hasMany(get_called_class(), 'translation_of');
    }
    
    
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */
    public function scopeTrans($builder)
    {
        return $builder->where('translation_lang', config('app.locale'));
    }
    
    public function scopeTransIn($builder, $languageCode)
    {
        return $builder->where('translation_lang', $languageCode);
    }
    

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */
    public function getTranslationOfAttribute()
    {
        $translationOf = (isset($this->attributes['translation_of'])) ? $this->attributes['translation_of'] : null;
        $entityId = (isset($this->attributes['id'])) ? $this->attributes['id'] : $translationOf;
        
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
