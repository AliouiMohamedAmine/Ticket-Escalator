<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    /**
     * All authenticated (or public) users may submit tickets.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     * Enum values are hardcoded — no user input reaches the rule definition.
     *
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'priority'    => ['required', 'in:low,medium,high'],
            'status'      => ['required', 'in:open,in_progress,resolved'],
        ];
    }

    /**
     * Human-readable error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'       => 'A ticket title is required.',
            'title.max'            => 'The title may not exceed 255 characters.',
            'description.required' => 'Please describe the issue.',
            'priority.required'    => 'Please select a priority level.',
            'priority.in'          => 'Priority must be low, medium, or high.',
            'status.required'      => 'Please select a status.',
            'status.in'            => 'Status must be open, in_progress, or resolved.',
        ];
    }
}
