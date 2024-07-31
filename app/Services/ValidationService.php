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

            case 'announcement':
                return $this->validateAnnouncement($request);

            case 'question':
                return $this->validateQuestion($request);

            case 'review':
                return $this->validateReview($request);

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
        } else {
            $rules['image'] = 'required';
        }

        return $rules;
    }

    protected function validateBanner(Request $request): array
    {
        $rules = [
            'title' => 'required',
            'subTitle' => 'required',
            'image' => 'required',
            'mobile_image' => 'required',
            'link' => 'nullable',
        ];

        if ($request->input('remove_image') == 0) {
            $rules['image'] = 'nullable';
        } elseif ($request->input('remove_image') == 1) {
            $rules['image'] = 'required';
        }

        if ($request->input('mobile_remove_image') == 0) {
            $rules['mobile_image'] = 'nullable';
        } elseif ($request->input('mobile_remove_image') == 1) {
            $rules['mobile_image'] = 'required';
        }

        return $rules;
    }

    protected function validateAnnouncement(Request $request): array
    {
        $rules = [
            'title' => 'required',
            'content' => 'required',
            'is_featured' => 'required|boolean',
            'file' => 'nullable|file|max:10240',
        ];

        return $rules;
    }

    protected function validateQuestion(Request $request): array
    {
        $rules = [
            'title' => 'required',
            'content' => 'required',
        ];

        return $rules;
    }

    protected function validateReview(Request $request): array
    {
        $rules = [
            'title' => 'required|string|max:255',
            'content' => 'required',
            'filter_category' => 'required',
            'filter_area' => 'required',
            'image' => 'nullable',
            'is_featured' => 'required|boolean',
            'file' => 'nullable',
        ];

        return $rules;
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
