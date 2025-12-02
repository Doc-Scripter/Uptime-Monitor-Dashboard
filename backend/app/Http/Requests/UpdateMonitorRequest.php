<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateMonitorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Add authentication logic later if needed
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $monitorId = $this->route('monitor');

        return [
            'name' => ['sometimes', 'string', 'max:255'],
            'url' => [
                'sometimes',
                'url',
                'max:2048',
                Rule::unique('monitors', 'url')->ignore($monitorId)
            ],
            'type' => ['sometimes', Rule::in(['website', 'api'])],
            'interval' => ['sometimes', 'integer', 'min:1', 'max:1440'],
            'is_active' => ['sometimes', 'boolean'],
            'tags' => ['sometimes', 'array'],
            'tags.*' => ['string', 'max:50'],
        ];
    }

    /**
     * Get custom error messages for validator.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'url.url' => 'Please provide a valid URL',
            'url.unique' => 'This URL is already being monitored by another monitor',
            'type.in' => 'Monitor type must be either website or api',
            'interval.min' => 'Check interval must be at least 1 minute',
            'interval.max' => 'Check interval cannot exceed 24 hours (1440 minutes)',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Ensure URL has protocol if URL is being updated
        if ($this->url && !preg_match('/^https?:\/\//', $this->url)) {
            $this->merge([
                'url' => 'https://' . $this->url
            ]);
        }
    }
}
