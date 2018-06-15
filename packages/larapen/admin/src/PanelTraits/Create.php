<?php

namespace Larapen\Admin\PanelTraits;

use App\Models\Language;

trait Create
{
    /*
    |--------------------------------------------------------------------------
    |                                   CREATE
    |--------------------------------------------------------------------------
    */

    /**
     * Insert a row in the database.
     *
     * @param $data
     * @return mixed
     */
    public function create($data)
    {
        $values_to_store = $this->compactFakeFields($data, 'create');
        $item = $this->model->create($values_to_store);

        // Create translations entries - If model has translatable fields
        $this->createTranslations($item, $values_to_store);

        // if there are any relationships available, also sync those
        $this->syncPivot($item, $data);

        return $item;
    }

    /**
     * Get all fields needed for the ADD NEW ENTRY form.
     *
     * @return mixed
     */
    public function getCreateFields()
    {
        return $this->create_fields;
    }

    /**
     * Get all fields with relation set (model key set on field).
     *
     * @param string $form
     * @return array
     */
    public function getRelationFields($form = 'create')
    {
        if ($form == 'create') {
            $fields = $this->create_fields;
        } else {
            $fields = $this->update_fields;
        }

        $relationFields = [];

        foreach ($fields as $field) {
            if (isset($field['model'])) {
                array_push($relationFields, $field);
            }

            if (isset($field['subfields']) &&
                is_array($field['subfields']) &&
                count($field['subfields'])) {
                foreach ($field['subfields'] as $subfield) {
                    array_push($relationFields, $subfield);
                }
            }
        }

        return $relationFields;
    }

    /**
     * @param $model
     * @param $data
     * @param string $form
     */
    public function syncPivot($model, $data, $form = 'create')
    {
        $fields_with_relationships = $this->getRelationFields($form);

        foreach ($fields_with_relationships as $key => $field) {
            if (isset($field['pivot']) && $field['pivot']) {
                $values = isset($data[$field['name']]) ? $data[$field['name']] : [];
                $model->{$field['name']}()->sync($values);

                if (isset($field['pivotFields'])) {
                    foreach ($field['pivotFields'] as $pivotField) {
                        foreach ($data[$pivotField] as $pivot_id =>  $field) {
                            $model->{$field['name']}()->updateExistingPivot($pivot_id, [$pivotField => $field]);
                        }
                    }
                }
            }

            if (isset($field['morph']) && $field['morph']) {
                $values = isset($data[$field['name']]) ? $data[$field['name']] : [];
                if ($model->{$field['name']}) {
                    $model->{$field['name']}()->update($values);
                } else {
                    $model->{$field['name']}()->create($values);
                }
            }
        }
    }

    /**
     * Fix the 'translation_of' field for the default language entry &
     * Create translations entries - If model has translatable fields
     *
     * @param $item
     * @param $values_to_store
     */
    private function createTranslations($item, $values_to_store)
    {
        if (property_exists($this->model, 'translatable')) {
            // Set 'translation_of' value when creating new entry.
            if (!empty($item)) {
                if ($item->hasAttribute('translation_of')) {
                    if (!isset($item->translation_of) || empty($item->translation_of)) {
                        $item->translation_of = $item->id;
                        $item->save();
                    }
                } else {
                    $item->setTranslationOfAttribute($item->id);
                    $item->save();
                }
            }

            // Copy-Paste for all languages
            $languages = Language::where('active', 1)->where('abbr', '!=', $item->translation_lang)->get();
            if (!empty($languages)) {
                foreach ($languages as $language) {
                    $values_to_store['translation_lang'] = $language->abbr;
                    $values_to_store['translation_of'] = $item->id;
                    $translatedItem = $this->model->create($values_to_store);
                }
            }
        }
    }
}
