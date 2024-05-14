<?php

namespace App\Filament\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class UniqueTogetherCustomRule implements Rule
{
    protected array $fields;
    protected Model $model;

    public function __construct(array $fields, Model $model)
    {
        $this->fields = $fields;
        $this->model = $model;
    }

    public function passes($attribute, $value): bool
    {
        $query = $this->model->newQuery();

        foreach ($this->fields as $field) {
            $query->where($field, request()->input($field));
        }

        $existingRecord = $query->first();

        return is_null($existingRecord) || $existingRecord->id === request()->route('id');
    }

    public function message(): string
    {
        $fieldNames = implode(', ', $this->fields);
        return "The combination of $fieldNames must be unique.";
    }
}
