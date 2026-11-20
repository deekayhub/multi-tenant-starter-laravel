<?php

namespace App\Http\Requests;

use App\Models\Tenant;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateWorkspaceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, ValidationRule|array<mixed>|string> */
    public function rules(): array
    {
        $tenant = tenant();
        $customDomain = $tenant?->customDomain();
        $customDomainId = $customDomain !== null
            ? $tenant->domains()->where('domain', $customDomain)->value('id')
            : null;
        $centralDomain = (string) config('tenancy.central_domains')[0];

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'lowercase', 'email', 'max:255'],
            'custom_domain' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^(?:[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?\.)+[a-z]{2,}$/i',
                Rule::unique('domains', 'domain')->ignore($customDomainId),
                function (string $attribute, mixed $value, callable $fail) use ($centralDomain): void {
                    if (is_string($value) && $value !== '' && Str::endsWith($value, '.'.$centralDomain)) {
                        $fail(__('Use the subdomain field for addresses under :domain.', ['domain' => $centralDomain]));
                    }
                },
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $customDomain = $this->string('custom_domain')->toString();

        $this->merge([
            'custom_domain' => $customDomain !== '' ? Tenant::normalizeHostname($customDomain) : null,
        ]);
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'custom_domain.regex' => __('Enter a valid domain, for example example.com.'),
            'custom_domain.unique' => __('That domain is already in use.'),
        ];
    }
}
