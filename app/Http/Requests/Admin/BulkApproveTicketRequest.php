<?php

namespace App\Http\Requests\Admin;

use App\Enums\TicketStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BulkApproveTicketRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'ticket_ids' => ['required', 'array', 'min:1'],
            'ticket_ids.*' => ['required', 'integer', 'exists:tickets,id'],
            'action' => ['required', 'string', Rule::in(['approve', 'reject'])],
            'message' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
