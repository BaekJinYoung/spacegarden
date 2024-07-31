<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InquiryRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string',
            'contact' => 'required|string',
            'inquiry_category' => 'nullable|string',
            'email' => 'nullable|string',
            'message' => 'nullable|required',
            'agreement' => [
                'required',
                'boolean',
                function ($attribute, $value, $fail) {
                    if ($value !== true) {
                        $fail('The ' . $attribute . ' field must be true.');
                    }
                },
            ],
        ];

        return $rules;
    }
}
