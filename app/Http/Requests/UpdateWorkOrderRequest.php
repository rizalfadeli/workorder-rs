<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // supaya tidak 403
    }

    public function rules(): array
    {
        return [
            'priority'       => ['required', 'in:high,medium,low'],
            'technician_id'  => ['nullable', 'exists:technicians,id'],
            'estimated_days' => ['nullable', 'integer', 'min:1', 'max:365'],
            'admin_notes'    => ['nullable', 'string', 'max:1000'],
        ];
    }
}