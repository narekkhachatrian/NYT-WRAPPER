<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ListSnapshotHttpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'list'           => ['required', 'regex:/^[a-z0-9\-]+$/'],
            'published_date' => [
                'sometimes',
                function ($attribute, $value, $fail) {
                    if ($value === 'current') {
                        return;
                    }
                    $dt = \DateTime::createFromFormat('Y-m-d', $value);
                    if (! $dt || $dt->format('Y-m-d') !== $value) {
                        $fail("{$attribute} must be 'current' or a valid date (YYYY-MM-DD).");
                    }
                },
            ],
            'offset'         => ['sometimes', 'integer', 'min:0', 'multiple_of:20'],
        ];
    }

    public function validatedOffset(): int
    {
        return (int) $this->integer('offset', 0);
    }

    public function validatedPublishedDate(): string
    {
        return $this->validated()['published_date'] ?? 'current';
    }
}
