<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSmsCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->isApproved();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:1000'],
            'sms_sender_id' => ['required', 'exists:sms_sender_ids,id'],
            'contact_group_id' => ['nullable', 'array', 'required_without:contacts'],
            'contact_group_id.*' => ['exists:contact_groups,id'],
            'scheduled_at' => ['nullable', 'date'],
            'contacts' => ['nullable', 'array', 'min:1', 'required_without:contact_group_id'],
            'contacts.*' => ['exists:contacts,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Campaign title is required',
            'message.required' => 'Message content is required',
            'message.max' => 'Message cannot exceed 1000 characters',
            'sms_sender_id.required' => 'Please select a Sender ID',
            'sms_sender_id.exists' => 'Selected Sender ID is invalid',
            'contacts.required' => 'Please select at least one contact or choose a contact group',
            'contacts.min' => 'At least one contact is required',
            'contact_group_id.required_without' => 'Please select a contact group or provide contacts',
        ];
    }
}
