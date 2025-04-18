<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class HistorySearchHttpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'author'    => ['required_without_all:title,isbn.*', 'string'],
            'title'     => ['required_without_all:author,isbn.*', 'string'],
            'isbn.*'    => [
                'required_without_all:author,title',
                'regex:/^(?:\d{13}|\d{9}[0-9Xx])$/',
            ],
            'offset'    => ['sometimes', 'integer', 'min:0', 'multiple_of:20'],
        ];
    }


    public function prepareForValidation(): void
    {
        if (!$this->hasAny(['author', 'title', 'isbn'])) {
            $this->merge(['author' => '']);
        }
    }

    public function validatedOffset(): int
    {
        return (int) $this->integer('offset', 0);
    }
}
