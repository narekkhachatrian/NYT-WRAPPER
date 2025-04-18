<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ReviewSearchHttpRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'author' => ['sometimes','string'],
            'title'  => ['sometimes','string'],
            'isbn'   => ['sometimes','regex:/^\d{10}(\d{3})?$/'],
        ];
    }

    public function prepareForValidation(): void
    {
        if (!$this->hasAny(['author','title','isbn'])) {
            $this->merge(['author' => '']);
        }
    }
}
