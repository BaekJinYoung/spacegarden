<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Inquiry;
use App\Models\Question;
use App\Models\Review;
use App\Models\Youtube;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class IndexController extends Controller
{
    private function fetchDataAndRespond($model, $selectColumns, $searchField, $page, Request $request) {
        $search = $request->input('search', '');
        $query = $model::select($selectColumns)->orderBy('id', 'desc');

        if (!is_null($searchField) && !empty($search)) {
            $query->where($searchField, 'like', '%' . $search . '%');
        }

        if (method_exists($model, 'scopeWithBooleanFormatted')) {
            $query->withBooleanFormatted();
        }

        if ($page > 0) {
            $pagination = $query->paginate($page);
            $dataCollection = $pagination->getCollection();
        } else {
            $dataCollection = $query->get();
        }

        $dataCollection = $dataCollection->map(function ($item) {
            return $this->formatItemWithImage($this->formatItem($item));
        });

        if ($page > 0) {
            $pagination->setCollection($dataCollection);
            $data = $pagination->toArray();
        } else {
            $data = [
                'data' => $dataCollection,
                'total' => $dataCollection->count()
            ];
        }

        $data['search'] = $search;

        return ApiResponse::success($data);
    }

    public function announcement(Request $request) {
        return $this->fetchDataAndRespond(Announcement::class, ['id', 'is_featured', 'title', 'created_at'], 'title', 10, $request);
    }

    public function question(Request $request) {
        return $this->fetchDataAndRespond(Question::class, ['id', 'title', 'content'], 'title', 10, $request);
    }

    public function review(Request $request) {
        return $this->fetchDataAndRespond(Review::class, ['id', 'image', 'filter_category', 'filter_area', 'title', 'content'], 'title', 10, $request);
    }

//    public function sns(Request $request) {
//        return $this->fetchDataAndRespond(Question::class, ['id', 'title', 'content'], 'title', 10, $request);
//    }

    private function formatItemWithImage($item) {
        $isBannerModel = $item instanceof Banner;

        $this->setImagePath($item, 'image');
        $this->setImagePath($item, 'mobile_image');

        return $item;
    }

    private function setImagePath($item, $field) {
        if (isset($item->$field)) {
            $imagePath = $item->$field;

            if (!preg_match('/^https?:\/\//', $imagePath)) {
                $imagePath = asset('storage/' . $imagePath);
            }

            $item->$field = $imagePath;

            $isBannerModel = $item instanceof Banner;

            if ($field === 'image' && $isBannerModel) {
                $fileType = $this->getFileType($imagePath);
                $item->image_type = ($fileType === 'image') ? 0 : 1;
            } elseif ($field === 'mobile_image' && $isBannerModel) {
                $fileType = $this->getFileType($imagePath);
                $item->mobile_image_type = ($fileType === 'image') ? 0 : 1;
            }
        }
    }

    private function getFileType($filePath)
    {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff'];
        $videoExtensions = ['mp4', 'mov', 'avi', 'mkv', 'webm'];

        if (in_array(strtolower($extension), $imageExtensions)) {
            return 'image';
        } elseif (in_array(strtolower($extension), $videoExtensions)) {
            return 'video';
        }

        return 'unknown';
    }

    private function formatItem($item) {
        foreach ($item->toArray() as $key => $value) {
            if (is_string($value)) {
                $item->$key = $this->convertNewlinesToBr($value);
            }
        }

        if (isset($item->created_at)) {
            $item->created_at_formatted = Carbon::parse($item->created_at)->format('Y.m.d');
            unset($item->created_at);
        }
        return $item;
    }

    private function convertNewlinesToBr($content) {
        return str_replace(["\r\n", "\r", "\n"], '<br/>', $content);
    }
}
