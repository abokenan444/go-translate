<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Rules\GovernmentEmailDomain;

class GovernmentRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Entity Information
            'entity_name' => ['required', 'string', 'max:255'],
            'entity_name_local' => ['nullable', 'string', 'max:255'],
            'entity_type' => [
                'required',
                Rule::in([
                    'ministry', 'embassy', 'consulate', 'municipality',
                    'agency', 'department', 'court', 'parliament', 'other'
                ])
            ],
            'country_code' => ['required', 'string', 'size:2'],
            'department' => ['nullable', 'string', 'max:255'],
            'official_website' => ['nullable', 'url', 'max:500'],

            // Contact Person
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_position' => ['required', 'string', 'max:255'],
            'contact_email' => [
                'required',
                'email',
                'max:255',
                new GovernmentEmailDomain(),
            ],
            'contact_phone' => ['nullable', 'string', 'max:50'],
            'employee_id' => ['nullable', 'string', 'max:100'],

            // Documents (at least one required)
            'documents' => ['required', 'array', 'min:1'],
            'documents.*.type' => [
                'required',
                Rule::in([
                    'official_id', 'authorization_letter', 'business_card',
                    'appointment_letter', 'official_website_proof', 'mou_agreement', 'other'
                ])
            ],
            'documents.*.file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png,doc,docx',
                'max:10240' // 10MB
            ],

            // Legal Disclaimer
            'legal_disclaimer_accepted' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'entity_name.required' => 'The official entity name is required.',
            'entity_type.required' => 'Please select the type of government entity.',
            'entity_type.in' => 'Invalid entity type selected.',
            'country_code.required' => 'Please select your country.',
            'contact_email.required' => 'An official email address is required.',
            'documents.required' => 'At least one supporting document is required.',
            'documents.min' => 'At least one supporting document is required.',
            'documents.*.file.max' => 'Each document must be less than 10MB.',
            'documents.*.file.mimes' => 'Documents must be PDF, JPG, PNG, DOC, or DOCX format.',
            'legal_disclaimer_accepted.accepted' => 'You must accept the legal disclaimer to proceed.',
        ];
    }

    public function attributes(): array
    {
        return [
            'entity_name' => 'entity name',
            'entity_name_local' => 'local entity name',
            'entity_type' => 'entity type',
            'country_code' => 'country',
            'contact_name' => 'contact person name',
            'contact_position' => 'position/title',
            'contact_email' => 'official email',
            'contact_phone' => 'phone number',
        ];
    }
}
