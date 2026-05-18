<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $nullable = ['nic', 'epf_no', 'designation', 'bank_account'];

        foreach ($nullable as $field) {
            if ($this->has($field) && trim((string) $this->input($field)) === '') {
                $this->merge([$field => null]);
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'company' => ['required', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'nic' => ['nullable', 'string', 'max:20'],
            'epf_no' => ['nullable', 'string', 'max:20'],
            'branch' => ['required', 'string', 'max:50'],
            'designation' => ['nullable', 'string', 'max:255'],
            'bank_account' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', Rule::in(['active', 'inactive'])],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Employee name is required.',
            'company.required' => 'Company is required.',
            'branch.required' => 'Branch is required.',
        ];
    }
}
