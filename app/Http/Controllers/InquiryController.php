<?php

namespace App\Http\Controllers;

use App\Http\Requests\InquiryRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController {
    public function __construct(Inquiry $inquiry) {
        $this->Inquiry = $inquiry;
    }

    public function store(InquiryRequest $request) {
        $inquiry = $request->validated();

        $this->Inquiry->create($inquiry);

        return ApiResponse::success($inquiry);
    }
}
