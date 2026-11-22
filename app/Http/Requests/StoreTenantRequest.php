<?php

namespace App\Http\Requests;

use App\Models\Tenant;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreTenantRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'workspace_name' => ['required', 'string', 'max:255'],
            'subdomain' => [
                'required',
                'string',
                'max:63',
                'regex:/^[a-z0-9](?:[a-z0-9-]{0,61}[a-z0-9])?$/',
                'not_in:'.implode(',', Tenant::RESERVED_SUBDOMAINS),
                Rule::unique('domains', 'domain'),
            ],
            'owner_name' => ['required', 'string', 'max:255'],
            'owner_email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'subdomain.regex' => __('Use lowercase letters, numbers and hyphens only.'),
            'subdomain.not_in' => __('That subdomain is reserved.'),
            'subdomain.unique' => __('That subdomain is already in use.'),
            'subdomain.required' => __('Choose a subdomain.'),
        ];
    }

    /**
     * Normalize the subdomain before validation runs.
     */
    protected function prepareForValidation(): void
    {
        $subdomain = $this->string('subdomain')->toString();

        $this->merge([
            'subdomain' => $subdomain !== '' ? Str::lower(trim(Str::replace(' ', '-', $subdomain))) : null,
            'owner_email' => $this->string('owner_email')->lower()->toString(),
        ]);
    }

    /**
     * Configure the validator instance.
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->has('subdomain')) {
                    return;
                }

                $fullDomain = $this->string('subdomain')->toString().'.'.config('tenancy.central_domains')[0];

                if (DB::table('domains')->where('domain', $fullDomain)->exists()) {
                    $validator->errors()->add('subdomain', __('That subdomain is already in use.'));
                }
            },
        ];
    }
}
