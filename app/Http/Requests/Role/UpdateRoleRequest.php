<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('roles.update');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $roleId = $this->route('role');

        return [
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($roleId)],
            'permission_ids' => ['sometimes', 'array'],
            'permission_ids.*' => ['integer', 'exists:permissions,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom du rôle est requis',
            'name.unique' => 'Ce nom de rôle existe déjà',
            'permission_ids.array' => 'Les permissions doivent être un tableau',
            'permission_ids.*.exists' => 'Une ou plusieurs permissions n\'existent pas',
        ];
    }
}
