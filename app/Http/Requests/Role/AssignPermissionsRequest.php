<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class AssignPermissionsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('permissions.assign');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'permission_ids' => ['required', 'array'],
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
            'permission_ids.required' => 'Au moins une permission est requise',
            'permission_ids.array' => 'Les permissions doivent être un tableau',
            'permission_ids.*.exists' => 'Une ou plusieurs permissions n\'existent pas',
        ];
    }
}
