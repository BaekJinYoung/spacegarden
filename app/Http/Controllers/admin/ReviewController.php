<?php

namespace App\Http\Controllers\admin;

use App\Models\Review;
use App\Services\ValidationService;

class ReviewController extends BaseController
{
    public function __construct(Review $review, ValidationService $validationService) {
        parent::__construct($review, $validationService);
        $this->setDefaultPerPage(8);
    }

    protected function getValidationContext(): string {
        return 'review';
    }
}
