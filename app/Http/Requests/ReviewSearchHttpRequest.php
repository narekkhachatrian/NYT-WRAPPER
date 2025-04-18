<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ReviewSearchHttpRequest extends FormRequest
{
    public function authorize(): bool { return true; }


    public function rules(): array
    {
        return [
            'isbn'   => [
                'required_without_all:author,title',
                'regex:/^(?:\d{13}|\d{9}[0-9Xx])$/'
            ],
            'title'  => ['required_without_all:isbn,author', 'string'],
            'author' => ['required_without_all:isbn,title', 'string'],
        ];
    }


    public function prepareForValidation(): void
    {
        if (!$this->hasAny(['author','title','isbn'])) {
            $this->merge(['author' => '']);
        }
    }
}
