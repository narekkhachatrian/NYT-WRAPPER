<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class HistorySearchHttpRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'author' => ['sometimes','string'],
            'title'  => ['sometimes','string'],
            'isbn'   => ['sometimes','array'],
            'isbn.*' => ['regex:/^\d{10}(\d{3})?$/'],
            'offset' => ['sometimes','integer','min:0','multiple_of:20'],
        ];
    }

    public function prepareForValidation(): void
    {
        if (!$this->hasAny(['author','title','isbn'])) {
            $this->merge(['author' => '']);
        }
    }

    public function validatedOffset(): int
    {
        return (int) $this->integer('offset', 0);
    }
}
