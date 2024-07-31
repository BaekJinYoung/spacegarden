<?php

namespace App\Http\Controllers\admin;

use App\Models\Inquiry;

class InquiryController extends BaseController {
    public function __construct(Inquiry $inquiry) {
        parent::__construct($inquiry);
    }
}
