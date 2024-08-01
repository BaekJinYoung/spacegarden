<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Announcement;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class DetailController extends Controller
{
    /**
     * 공지사항 상세 페이지
     *
     * 주어진 공지사항 ID에 대한 상세 정보를 반환합니다.
     *
     * @group Announcements
     * @queryParam id required 공지사항 ID. Example: null
     *
     * @response 200 {
     *   "success": true, // true-정상 호출 / false-호출 오류
     *   "message": "Success", // Success-정상 호출 / Error-호출 오류
     *   "data": {
     *     "id": 2, // 공지사항 ID
     *     "title": "제목", // 제목
     *     "created_at_formatted": "2024-08-01", // 작성일, Y-m-d 형식
     *     "views": 1, // 조회수
     *     "content": "내용", // 내용
     *     "file_name": "TEST_모바일.jpg", // 첨부파일 파일명
     *     "file": "http://54.180.236.140/storage/uploads/TEST_모바일.jpg", // 첨부파일 URL
     *     "prev": { // 이전 공지사항 정보, 이전 데이터가 없을 시 null
     *       "id": 1, // 이전 공지사항의 ID
     *       "title": "제목" // 이전 공지사항 제목
     *     },
     *     "next": null // 다음 공지사항 정보, 다음 데이터가 없을 시 null
     *   }
     * }
     */

    public function announcement_detail($id) {
        return $this->detailRespond(Announcement::class, ['id', 'title', 'views', 'content', 'file', 'created_at'], $id, true, true);
    }

    /**
     * 고객 후기 상세 페이지
     *
     * 주어진 고객후기 ID에 대한 상세 정보를 반환합니다.
     *
     * @group Reviews
     * @queryParam id required 고객후기 ID. Example: null
     *
     * @response 200 {
     *   "success": true, // true-정상 호출 / false-호출 오류
     *   "message": "Success", // Success-정상 호출 / Error-호출 오류
     *   "data": {
     *     "id": 4, // 고객후기 ID
     *     "title": "제목", // 제목
     *     "created_at_formatted": "2024-08-01", // 작성일, 형식: "Y-m-d"
     *     "views": 2, // 조회수
     *     "filter_category": "부분 정리수납", // 필터 유형: 전체 정리수납/부분 정리수납/원스톱 토탈서비스
     *     "filter_area": "원룸", // 필터 평수: 원룸/10평대/20평대/30평대/40평대/50평대 이상
     *     "content": "내용", // 내용
     *     "image": "http://54.180.236.140/storage/images/TEST_모바일.jpg", // 대표사진 URL
     *     "file_name": "TEST_모바일.jpg", // 첨부파일 파일명
     *     "file": "http://54.180.236.140/storage/uploads/TEST_모바일.jpg", // 첨부파일 URL
     *     "prev": { // 이전 후기 정보, 이전 데이터가 없을 시 null
     *       "id": 3, // 이전 후기 ID
     *       "title": "제목" // 이전 후기 제목
     *     },
     *     "next": null // 다음 후기 정보, 다음 데이터가 없을 시 null
     *   }
     * }
     */

    public function review_detail($id) {
        return $this->detailRespond(Review::class, ['id', 'title', 'filter_category', 'filter_area', 'image', 'views', 'content', 'file', 'created_at'], $id, true, true);
    }

    private function detailRespond($model, $selectColumns, $id, $incrementViews = false, $prevNext = false) {
        $detail = $model::select($selectColumns)->findOrFail($id);

        if ($incrementViews) {
            $detail->increment('views');
        }

        if ($prevNext) {
            $this->setPrevNextDetails($model, $detail);
        }

        $detail['created_at_formatted'] = Carbon::parse($detail['created_at'])->format('Y-m-d');
        unset($detail['created_at']);

        $detail = $this->formatFileData($detail);

        foreach ($detail as $key => $value) {
            if (is_string($value)) {
                $detail[$key] = $this->convertNewlinesToBr($value);
            }
        }

        $fieldsOrder = [
            'id',
            'title',
            'created_at_formatted',
            'views',
            'filter_category',
            'filter_area',
            'content',
            'image',
            'file_name',
            'file',
            'prev',
            'next'
        ];

        $orderedDetail = $this->reorderFields($detail, $fieldsOrder);

        return ApiResponse::success($orderedDetail);
    }

    private function setPrevNextDetails($model, $detail) {
        $prevDetail = $model::select('id', 'title')
            ->where('id', '<', $detail->id)
            ->orderBy('id', 'desc')
            ->first();

        $nextDetail = $model::select('id', 'title')
            ->where('id', '>', $detail->id)
            ->orderBy('id', 'asc')
            ->first();

        $detail->prev = $prevDetail ? $prevDetail : null;
        $detail->next = $nextDetail ? $nextDetail : null;
    }

    private function formatFileData($detail) {
        $detail = $this->checkFileExists($detail, 'image');
        $detail = $this->checkFileExists($detail, 'file');

        return $detail;
    }

    private function checkFileExists($detail, $field) {
        if (array_key_exists($field, $detail->toArray())) {
            $filePath = $detail->$field;
            $fileExists = isset($filePath) && Storage::exists('public/' . $filePath);

            if ($fileExists) {
                $detail[$field] = asset('storage/' . $filePath);
                if ($field === 'file') {
                    $detail['file_name'] = pathinfo($filePath, PATHINFO_FILENAME) . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
                }
            } else {
                $detail[$field] = null;
                if ($field === 'file') {
                    $detail['file_name'] = null;
                }
            }
        } else {
            unset($detail[$field]);
            if ($field === 'file') {
                unset($detail['file_name']);
            }
        }

        return $detail;
    }

    private function convertNewlinesToBr($content) {
        return str_replace(["\r\n", "\r", "\n"], '<br/>', $content);
    }

    private function reorderFields($detail, $fieldsOrder) {
        $orderedDetail = [];
        foreach ($fieldsOrder as $field) {
            if (array_key_exists($field, $detail->toArray())) {
                $orderedDetail[$field] = $detail[$field];
            }
        }
        return $orderedDetail;
    }

    public function downloadFile(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'model' => 'required|string|in:announcement,company,share'
        ]);

        $modelClass = $this->getModelClass($request->input('model'));

        $fileDetail = $modelClass::select(['file_path'])->findOrFail($request->input('id'));

        if (!$fileDetail->file_path || !Storage::exists('public/' . $fileDetail->file_path)) {
            return ApiResponse::error('파일이 존재하지 않습니다.', 404);
        }

        return Storage::download('public/' . $fileDetail->file_path);
    }

    private function getModelClass($model)
    {
        switch ($model) {
            case 'announcement':
                return Announcement::class;
            default:
                throw new \InvalidArgumentException('올바르지 않은 모델입니다.');
        }
    }
}
