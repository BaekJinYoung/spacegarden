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
    public function announcement_detail($id) {
        return $this->detailRespond(Announcement::class, ['id', 'title', 'views', 'content', 'file', 'created_at'], $id, true, true);
    }

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
