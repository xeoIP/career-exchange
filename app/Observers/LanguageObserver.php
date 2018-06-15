<?php

namespace App\Observer;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Prologue\Alerts\Facades\Alert;

class LanguageObserver
{
    // Translated models with their relations
    private $translatedModels = [
        'PostType'   => [['name' => 'Post', 'key' => 'post_type_id']],
        'Category' => [
            ['name' => 'Category', 'key' => 'parent_id'],
            ['name' => 'Post', 'key' => 'category_id'],
        ],
        'Gender'   => [['name' => 'User', 'key' => 'gender_id']],
        'Package'  => [['name' => 'Payment', 'key' => 'package_id']],
        'ReportType',
        'Page',
        'SalaryType' => [['name' => 'Post', 'key' => 'salary_type_id']],
        'MetaTag',
    ];
    
    // Get models namespace
    private $namespace = '\\App\Models\\';
    
    
    /**
     * Listen to the Entry creating event.
     *
     * @param  Language $language
     * @return void
     */
    public function creating(Language $language)
    {
        // Get the current Default Language
        $defaultLang = Language::where('default', 1)->first();
    
        // Copy the english language folder to the new language folder
        File::copyDirectory(resource_path('lang/' . $defaultLang->abbr), resource_path('lang/' . Input::get('abbr')));
        File::copyDirectory(resource_path('lang/vendor/admin/' . $defaultLang->abbr), resource_path('lang/vendor/admin/' . Input::get('abbr')));
    
        // Create translated entries
        $this->createTranslatedEntries($defaultLang->abbr, Input::get('abbr'));
    }
    
    /**
     * Listen to the Entry updating event.
     *
     * @param  Language $language
     * @return void
     */
    public function updating(Language $language)
    {
        // Set default language
        if (Input::filled('default')) {
            if (Input::get('default') == 1 || Input::get('default') == 'on') {
                // Update translated entries
                $this->updateTranslatedEntries(Input::get('abbr'));
            
                // Set default language
                $this->setDefaultLanguage(Input::get('abbr'));
            }
        }
    }
    
    /**
     * Listen to the Entry deleting event.
     *
     * @param Language $language
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleting(Language $language)
    {
        // Don't delete the default language
        if ($language->abbr == config('applang.abbr')) {
            Alert::error("You cannot delete the default language.")->flash();
            return back();
        }
    
        // Delete all translated entries
        $this->destroyTranslatedEntries($language->abbr);
    
        // Remove all language files
        File::deleteDirectory(resource_path('lang/' . $language->abbr));
        File::deleteDirectory(resource_path('lang/vendor/admin/' . $language->abbr));
    }
    
    /**
     * Listen to the Entry saved event.
     *
     * @param  Language $language
     * @return void
     */
    public function saved(Language $language)
    {
        // Removing Entries from the Cache
        $this->clearCache($language);
    }
    
    /**
     * Listen to the Entry deleted event.
     *
     * @param  Language $language
     * @return void
     */
    public function deleted(Language $language)
    {
        // Removing Entries from the Cache
        $this->clearCache($language);
    }
    
    
    
    // PRIVATE METHODS
    
    
    
    /**
     * CREATING - Create translated entries
     *
     * @param $defaultLangAbbr
     * @param $abbr
     */
    private function createTranslatedEntries($defaultLangAbbr, $abbr)
    {
        // Create Translated Models entries
        foreach($this->translatedModels as $name => $relations) {
            // Fix models without relations
            if (is_numeric($name) && is_string($relations)) {
                $name = $relations;
            }
            $model = $this->namespace . $name;
            
            // Get the model's main entries
            $mainEntries = $model::where('translation_lang', strtolower($defaultLangAbbr))->get();
            if ($mainEntries->count() > 0) {
                foreach($mainEntries as $entry) {
                    $newEntryInfo = $entry->toArray();
                    $newEntryInfo['translation_lang'] = strtolower($abbr);
                    
                    // Save newEntry to database
                    $newEntry = new $model($newEntryInfo);
                    $newEntry->save();
                }
            }
        }
    }
    
    /**
     * UPDATING - Update translated entries
     *
     * @param $abbr
     */
    private function updateTranslatedEntries($abbr)
    {
        // Update Translated Models entries
        foreach($this->translatedModels as $name => $relations) {
            // Fix models without relations
            if (is_numeric($name) && is_string($relations)) {
                $name = $relations;
            }
            $model = $this->namespace . $name;
            
            // Get new "translation_of" value with old entries
            $tmpEntries = $model::where('translation_lang', strtolower($abbr))->get();
            $newTid = [];
            if ($tmpEntries->count() > 0) {
                foreach($tmpEntries as $tmp) {
                    $newTid[$tmp->translation_of] = $tmp->id;
                }
            }
            
            // Change "translation_of" value with new Default Language
            $entries = $model::all();
            if ($entries->count() > 0) {
                foreach($entries as $entry) {
                    if (isset($newTid[$entry->translation_of])) {
                        $entry->translation_of = $newTid[$entry->translation_of];
                        $entry->save();
                    }
                }
            }
            
            // If relation exists, change its foreign key value
            if (isset($relations) && is_array($relations) && !empty($relations)) {
                foreach($relations as $relation) {
                    if (!isset($relation) || !isset($relation['key']) || !isset($relation['name'])) {
                        continue;
                    }
                    $relModel = $this->namespace . $relation['name'];
                    $relEntries = $relModel::all();
                    if ($relEntries->count() > 0) {
                        foreach($relEntries as $relEntry) {
                            if (isset($newTid[$relEntry->{$relation['key']}])) {
                                // Update the relation entry
                                $relEntry->{$relation['key']} = $newTid[$relEntry->{$relation['key']}];
                                $relEntry->save();
                            }
                        }
                    }
                }
            }
        }
    }
    
    /**
     * UPDATING - Set default language (Call this method at last)
     *
     * @param $abbr
     */
    private function setDefaultLanguage($abbr)
    {
        // Unset the old default language
        Language::whereIn('active', [0, 1])->update(['default' => 0]);
        
        // Set the new default language
        Language::where('abbr', $abbr)->update(['default' => 1]);
    }
    
    /**
     * DELETING - Delete translated entries
     *
     * @param $abbr
     */
    private function destroyTranslatedEntries($abbr)
    {
        // Remove Translated Models entries
        foreach($this->translatedModels as $name => $relations) {
            // Fix models without relations
            if (is_numeric($name) && is_string($relations)) {
                $name = $relations;
            }
            $model = $this->namespace . $name;
            
            // Get the model's main entries
            $translatedEntries = $model::where('translation_lang', strtolower($abbr))->get();
            if ($translatedEntries->count() > 0) {
                foreach($translatedEntries as $entry) {
                    // Delete
                    $entry->delete();
                }
            }
        }
    }
    
    /**
     * Removing the Entity's Entries from the Cache
     *
     * @param $language
     */
    private function clearCache($language)
    {
        Cache::flush();
    }
}
