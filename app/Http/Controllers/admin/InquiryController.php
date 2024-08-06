<?php

namespace App\Http\Controllers\admin;

use App\Models\Inquiry;
use App\Services\ValidationService;

class InquiryController extends BaseController {
    public function __construct(Inquiry $inquiry, ValidationService $validationService) {
        parent::__construct($inquiry, $validationService);
        $this->setDefaultPerPage(10);
    }
}
