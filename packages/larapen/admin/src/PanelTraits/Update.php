<?php

namespace Larapen\Admin\PanelTraits;

use App\Models\Language;

trait Update
{
    /*
    |--------------------------------------------------------------------------
    |                                   UPDATE
    |--------------------------------------------------------------------------
    */

    /**
     * Update a row in the database.
     *
     * @param  [Int] The entity's id
     * @param  [Request] All inputs to be updated.
     *
     * @return [Eloquent Collection]
     */
    public function update($id, $data)
    {
        $item = $this->model->findOrFail($id);
        $values_to_store = $this->compactFakeFields($data, 'update');
        $updated = $item->update($values_to_store);

        // Update translations entries - If model has translatable fields
        $this->updateTranslations($item, $values_to_store);

        $this->syncPivot($item, $data, 'update');

        return $item;
    }

    /**
     * Get all fields needed for the EDIT ENTRY form.
     *
     * @param  [integer] The id of the entry that is being edited.
     * @param int $id
     *
     * @return [array] The fields with attributes, fake attributes and values.
     */
    public function getUpdateFields($id)
    {
        $fields = $this->update_fields;
        $entry = $this->getEntry($id);

        foreach ($fields as $k => $field) {
            // set the value
            if (! isset($fields[$k]['value'])) {
                if (isset($field['subfields'])) {
                    $fields[$k]['value'] = [];
                    foreach ($field['subfields'] as $key => $subfield) {
                        $fields[$k]['value'][] = $entry->{$subfield['name']};
                    }
                } else {
                    $fields[$k]['value'] = $entry->{$field['name']};
                }
            }
        }

        // always have a hidden input for the entry id
        $fields['id'] = [
                        'name'  => $entry->getKeyName(),
                        'value' => $entry->getKey(),
                        'type'  => 'hidden',
                    ];

        return $fields;
    }

    /**
     * Update translations entries - If model has translatable fields
     *
     * @param $item
     * @param $values_to_store
     */
    private function updateTranslations($item, $values_to_store)
    {
        if (property_exists($this->model, 'translatable')) {
            // If the entry is a default language entry, copy-paste its translations common data
            if ($item->id == $item->translation_of) {
                // ... AND don't select the current translated entry to prevent infinite recursion
                $entries = $this->model->where('id', '!=', $item->id)->where('translation_of', $item->translation_of)->get();

                // Copy-Paste for all languages
                if (!empty($entries)) {
                    foreach ($entries as $entry) {
                        // Update the entry values
                        foreach ($values_to_store as $field => $value) {
                            // Reject all non fillable fields
                            if (!$this->model->isFillable($field)) {
                                continue;
                            }
                            // Don't overwrite translatable data
                            if (in_array($field, $this->model->translatable)) {
                                continue;
                            }
                            $entry->{$field} = $value;
                        }
                        // Save
                        $entry->save();
                    }
                }
            }
        }
    }
}
