<?php

namespace App\Http\Requests;

use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StoreMemberRequest extends FormRequest
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

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles' => ['sometimes', 'array'],
            'roles.*' => [
                'integer',
                Rule::exists('roles', 'id')->where(fn (Builder $query): Builder => $query
                    ->where($teamKey, getPermissionsTeamId())
                    ->where('guard_name', $guard)),
            ],
        ];
    }
}
