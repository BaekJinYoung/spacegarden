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

    /**
     * Store a new inquiry.
     *
     * 사용자로부터 받은 문의 정보를 데이터베이스에 저장합니다.
     * 저장이 완료된 후, 저장된 데이터와 함께 성공 응답을 반환합니다.
     *
     * @group Inquiries
     *
     * @param InquiryRequest $request The request object containing validated inquiry data.
     *
     * @bodyParam name string required 이름. Example: null
     * @bodyParam contact string required 연락처. Example: null
     * @bodyParam inquiry_category string 문의 유형. Example: null
     * @bodyParam email string 이메일. Example: null
     * @bodyParam message string 문의 내용. Example: null
     * @bodyParam agreement boolean required 개인정보 제공 동의 여부. Example: null
     *
     * @response 200 {
     *     "success": true, // 요청이 성공적으로 처리되었음을 나타냅니다.
     *     "message": "Success", // 요청이 성공적으로 처리되었음을 나타냅니다.
     *     "data": {
     *         "name": "이름", // 이름
     *         "contact": "010-1234-5678", // 연락처
     *         "inquiry_category": "문의 유형", // 문의 유형
     *         "email": "admin@email.com", // 이메일
     *         "message": "문의 내용", // 문의 내용
     *         "agreement": true // 개인정보 제공 동의 여부 (true/false)
     *     }
     * }
     *
     * @response 400 {
     *     "success": false, // 요청이 실패했음을 나타냅니다.
     *     "message": "Validation Error", // 유효성 검사 오류 메시지
     *     "errors": { // 실패한 유효성 검사 항목과 오류 메시지
     *        // 작성 중
     *     }
     * }
     */

    public function store(InquiryRequest $request) {
        $inquiry = $request->validated();

        $this->Inquiry->create($inquiry);

        return ApiResponse::success($inquiry);
    }
}
