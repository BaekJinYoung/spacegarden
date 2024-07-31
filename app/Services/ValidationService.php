<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;

class ValidationService
{
    public function validate(Request $request, string $context): array
    {
        $rules = $this->getValidationRules($request, $context);

        return $this->performValidation($request, $rules);
    }

    public function getValidationRules(Request $request, string $context): array
    {
        switch ($context) {
            case 'popup':
                return $this->validatePopup($request);

            case 'banner':
                return $this->validateBanner($request);

            case 'question':
                return $this->validateQuestion($request);

            default:
                throw new InvalidArgumentException('Invalid validation context: ' . $context);
        }
    }

    protected function validatePopup(Request $request): array
    {
        $rules = [
            'title' => 'required',
            'link' => 'nullable',
            'image' => 'required',
        ];

        if ($request->input('remove_image') == 0) {
            $rules['image'] = 'nullable';
        }

        return $rules;
    }

    protected function validateBanner(Request $request): array
    {
        $rules = [
            'title' => 'required',
            'image' => 'required',
            'mobile_title' => 'required',
            'mobile_image' => 'required',
        ];

        if ($request->input('remove_image') == 0) {
            $rules['image'] = 'nullable';
        }

        return $rules;
    }

    protected function validateQuestion(Request $request): array
    {
        $rules = ['title' => 'required|string|max:255', 'content' => 'required|string',];

        return $this->performValidation($request, $rules);
    }

    protected function performValidation(Request $request, array $rules): array
    {
        try {
            return $request->validate($rules);
        } catch (ValidationException $e) {
// Handle validation exceptions if needed
            throw $e;
        }
    }
}