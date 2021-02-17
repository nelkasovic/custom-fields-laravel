<?php

namespace Wimando\LaravelCustomFields\Traits;

use Wimando\LaravelCustomFields\Models\CustomField;
use Wimando\LaravelCustomFields\Models\CustomFieldResponse;

trait HasCustomFieldResponses
{
    public function customFieldResponses()
    {
        return $this->morphMany(CustomFieldResponse::class, 'model');
    }

    public function saveCustomFields($fields)
    {
        foreach ($fields as $key => $value) {
            $customField = CustomField::find((int) $key);

            if (!$customField) {
                continue;
            }

            CustomFieldResponse::create([
                'value' => $value,
                'field_id' => $customField->id,
                'model_id' => $this->id,
                'model_type' => get_class($this),
            ]);
        }
    }

    public function scopeWhereField($query, CustomField $field, $value)
    {
        $query->whereHas('customFieldResponses', function ($subQuery) use ($field, $value) {
            $subQuery
                ->where('field_id', $field->id)
                ->hasValue($value);
        });
    }
}
