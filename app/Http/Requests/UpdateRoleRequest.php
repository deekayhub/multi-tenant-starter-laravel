<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        $teamKey = (string) config('permission.column_names.team_foreign_key');
        $guard = (string) config('auth.defaults.guard');
        $roleId = $this->route('role')?->getKey();

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\pL\pN _-]+$/u',
                Rule::unique('roles', 'name')->where(fn (Builder $query): Builder => $query
                    ->where($teamKey, getPermissionsTeamId())
                    ->where('guard_name', $guard))->ignore($roleId),
            ],
            'permissions' => ['required', 'array', 'min:1'],
            'permissions.*' => [
                'string',
                Rule::exists('permissions', 'name')->where(fn (Builder $query): Builder => $query
                    ->where('guard_name', $guard)),
            ],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'name.regex' => __('Use letters, numbers, spaces, hyphens and underscores only.'),
            'permissions.required' => __('Select at least one permission.'),
        ];
    }
}
