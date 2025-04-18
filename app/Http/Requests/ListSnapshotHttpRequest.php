<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ListSnapshotHttpRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'list'            => ['required','string','regex:/^[a-z0-9\-]+$/'],
            'published_date'  => ['sometimes','regex:/^(current|\d{4}-\d{2}-\d{2})$/'],
            'offset'          => ['sometimes','integer','min:0','multiple_of:20'],
        ];
    }

    public function validatedOffset(): int
    {
        return (int) $this->integer('offset', 0);
    }

    public function validatedPublishedDate(): string
    {
        return $this->input('published_date', 'current');
    }
}
