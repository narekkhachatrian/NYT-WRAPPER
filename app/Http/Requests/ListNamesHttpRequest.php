<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

final class ListNamesHttpRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}
