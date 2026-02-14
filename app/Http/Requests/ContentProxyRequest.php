<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Enums\ExternalContentProxyType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ContentProxyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return []; // validation done via route and below
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var ExternalContentProxyType|null $type */
            $type = $this->route('type');
            $extension = $this->route('extension');

            if ($type instanceof ExternalContentProxyType && $extension !== $type->extension()) {
                abort(404);
            }
        });
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'identifier' => $this->route('identifier'),
            'extension' => $this->route('extension'),
        ]);
    }
}
