<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Models\Announcement;
use App\Models\Banner;
use App\Models\Inquiry;
use App\Models\Popup;
use App\Models\Question;
use App\Models\Review;
use App\Models\Youtube;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class IndexController extends Controller
{
    /**
     * Retrieve the main response data including popups, banners, YouTube videos, and reviews.
     *
     * 팝업, 배너, 유튜브, 고객 후기를 포함한 메인 페이지 데이터를 가져옵니다.
     *
     * @group Main
     *
     * @response 200 {
     *   "success": true, // true-정상 호출 / false-호출 오류
     *   "message": "Success", // Success-정상 호출 / 게시물이 없습니다.-정상 호출, 데이터 없음 / 검색 결과가 없습니다. 검색어:-정상 호출, 검색 결과 없음 / Error-호출 오류
     *   "data": {
     *     "popup": [
     *       {
     *         "id": 3, // 팝업 ID
     *         "title": "팝업 링크", // 팝업 제목
     *         "image": "http://43.201.247.176/storage/images/TEST_모바일.jpg", // 이미지 URL
     *         "link": "https://www.youtube.com/" // 팝업 링크, 데이터가 없을 시 null
     *       }
     *     ],
     *     "banner": [
     *       {
     *         "id": 2, // 배너 ID
     *         "title": "영상 테스트", // 배너 제목
     *         "subTitle": "소제목", // 배너 소제목
     *         "image": "http://43.201.247.176/storage/images/대패삼겹살 덮밥 -- 부타동 -- 덮밥 레시피 -- 대패삼겹살 요리.mp4", // 사진 혹은 동영상 URL
     *         "mobile_image": "http://43.201.247.176/storage/images/대패삼겹살 덮밥 -- 부타동 -- 덮밥 레시피 -- 대패삼겹살 요리.mp4", // 모바일 사진 혹은 동영상 URL
     *         "link": "https://www.youtube.com/", // 배너 링크
     *         "image_type": 1, // 이미지 타입 (0: 이미지, 1: 비디오)
     *         "mobile_image_type": 1 // 모바일 이미지 타입 (0: 이미지, 1: 비디오)
     *       }
     *     ],
     *     "youtube": [ // 최대 9개 노출
     *       {
     *         "id": 1, // 유튜브 ID
     *         "title": "메인 노출", // 제목
     *         "created_at_formatted": "2024.08.01", // 게시일, 형식: "Y.m.d"
     *         "video_id": "ncNL6tP_dsI" // 유튜브 비디오 ID
     *       }
     *     ],
     *     "reviews": [ // 최대 9개 노출
     *       {
     *         "id": 1, // 고객후기 ID
     *         "title": "메인 화면 노출", // 제목
     *         "content": "내용</p><p><br></p><p><br></p><p>이미지 삽입</p><p><br></p><p><img src=\"http://43.201.247.176/storage/images/TEST_모바일.jpg\">" // 후기 내용, HTML 형식
     *       }
     *     ]
     *   }
     * }
     */

    public function mainRespond() {
        $popup = $this->fetchAndFormat(Popup::class, ['id', 'title', 'image','link'], 0);
        $banner = $this->fetchAndFormat(Banner::class, ['id', 'title', 'subTitle', 'image', 'mobile_image', 'link'], 0, false, 'asc');
        $youtubes = $this->fetchAndFormat(Youtube::class, ['id', 'title', 'link', 'created_at'], 9, true);
        $reviews = $this->fetchAndFormat(Review::class, ['id', 'image', 'title', 'content'], 9, true);

        $main = [
            'popup' => $popup,
            'banner' => $banner,
            'youtube' => $youtubes,
            'reviews' => $reviews,
        ];

        return ApiResponse::success($main);
    }

    private function fetchAndFormat($model, $selectColumns, $limit, $isFeatured = false, $sortDirection = 'desc') {
        if (!in_array($sortDirection, ['asc', 'desc'])) {
            $sortDirection = 'desc';
        }

        $query = $model::select($selectColumns)
            ->orderBy('id', $sortDirection);

        if ($isFeatured) {
            $query->where('is_featured', true);
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        $data = $query->get();

        if ($data->isEmpty()) {
            return null;
        }

        $data = $data->map(function ($item) {
            return $this->formatItemWithImage($this->formatItem($item));
        });

        $isYoutubeModel = $model === Youtube::class;

        $data = $data->map(function ($item) use ($isYoutubeModel) {
            if ($isYoutubeModel && isset($item->link)) {
                $youtubeVideoId = $this->extractYoutubeVideoId($item->link);
                $item->video_id = $youtubeVideoId;
                unset($item->link);
            }

            return $this->formatItemWithImage($item);
        });

        $formattedData = $this->formatCollection($data);

        if ($formattedData->isEmpty()) {
            return ApiResponse::success([], '게시물이 없습니다.');
        }

        return $formattedData;
    }

    private function extractYoutubeVideoId($url) {
        preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i', $url, $matches);
        return $matches[1] ?? null;
    }

    private function formatCollection($collection) {
        return $collection->transform(function ($item) {
            return $this->formatItem($item);
        });
    }

    /**
     * Retrieve a list of announcements.
     *
     * 공지사항 목록을 가져옵니다. 각 게시물은 ID, 제목, 메인 표출 여부, 생성 날짜를 포함합니다.
     *
     * @group Announcements
     *
     * @queryParam search string 검색할 게시물의 제목입니다. 검색을 하지 않을 경우 빈값( ""||null)을 입력합니다. Example: null
     * @queryParam page integer 페이지 번호입니다. 기본값은 null입니다. Example: null
     *
     * @response 200 {
     *   "success": true, // true-정상 호출 / false-호출 오류
     *   "message": "Success", // Success-정상 호출 / 게시물이 없습니다.-정상 호출, 데이터 없음 / 검색 결과가 없습니다. 검색어:-정상 호출, 검색 결과 없음 / Error-호출 오류
     *   "data": {
     *     "current_page": 1, // 현재 페이지 번호
     *     "data": [
     *       {
     *         "id": 1, // 공지사항 ID
     *         "title": "New Announcement", // 공지사항 제목
     *         "is_featured": true, // 메인 페이지 표출 여부 (true-Y / false-N)
     *         "created_at_formatted": "2024.07.31" // 작성일, 형식: "Y.m.d"
     *       }
     *     ],
     *     "first_page_url": "http://43.201.247.176/api/announcement?page=1", // 첫 페이지 URL
     *     "from": 1, // 현재 페이지의 첫 번째 항목 번호
     *     "last_page": 1, // 총 페이지 수
     *     "last_page_url": "http://43.201.247.176/api/announcement?page=1", // 마지막 페이지 URL
     *     "links": [
     *       {
     *         "url": null, // 이전 페이지 URL, 이전 페이지가 없으면 null
     *         "label": "&laquo; Previous", // 이전 페이지 링크 레이블
     *         "active": false // 이전 페이지 링크 활성화 여부
     *       },
     *       {
     *         "url": "http://43.201.247.176/api/announcement?page=1", // 현재 페이지 URL
     *         "label": "1", // 현재 페이지 링크 레이블
     *         "active": true // 현재 페이지 링크 활성화 여부
     *       },
     *       {
     *         "url": null, // 다음 페이지 URL, 다음 페이지가 없으면 null
     *         "label": "Next &raquo;", // 다음 페이지 링크 레이블
     *         "active": false // 다음 페이지 링크 활성화 여부
     *       }
     *     ],
     *     "next_page_url": null, // 다음 페이지 URL
     *     "path": "http://43.201.247.176/api/announcement", // API 기본 URL
     *     "per_page": 10, // 페이지당 항목 수
     *     "prev_page_url": null, // 이전 페이지 URL, 이전 페이지가 없으면 null
     *     "to": 1, // 현재 페이지의 마지막 항목 번호
     *     "total": 1, // 총 항목 수
     *     "search": "" // 제목 검색어, 검색어가 없으면 빈 문자열
     *   }
     * }
     */

    public function announcement(Request $request) {
        return $this->fetchDataAndRespond(Announcement::class, ['id', 'is_featured', 'title', 'created_at'], 'title', 10, $request);
    }

    /**
     * Retrieve a list of questions.
     *
     * 자주묻는 질문 목록을 가져옵니다. 각 질문은 ID, 제목, 내용이 포함됩니다.
     *
     * @group Questions
     *
     * @queryParam search string 검색할 질문의 제목입니다. 검색을 하지 않을 경우 빈값( ""||null)을 입력합니다. Example: null
     * @queryParam page integer 페이지 번호입니다. 기본값은 null입니다. Example: null
     *
     * @response 200 {
     *   "success": true, // true-정상 호출 / false-호출 오류
     *   "message": "Success", // Success-정상 호출 / 게시물이 없습니다.-정상 호출, 데이터 없음 / 검색 결과가 없습니다. 검색어:-정상 호출, 검색 결과 없음 / Error-호출 오류
     *   "data": {
     *     "current_page": 1, // 현재 페이지 번호
     *     "data": [
     *       {
     *         "id": 1, // 질문 ID
     *         "title": "제목", // 질문 제목
     *         "content": "내용" // 질문 내용
     *       }
     *     ],
     *     "first_page_url": "http://43.201.247.176/api/question?page=1", // 첫 페이지 URL
     *     "from": 1, // 현재 페이지의 첫 번째 항목 번호
     *     "last_page": 1, // 총 페이지 수
     *     "last_page_url": "http://43.201.247.176/api/question?page=1", // 마지막 페이지 URL
     *     "links": [
     *       {
     *         "url": null, // 이전 페이지 URL, 이전 페이지가 없으면 null
     *         "label": "&laquo; Previous", // 이전 페이지 링크 레이블
     *         "active": false // 이전 페이지 링크 활성화 여부
     *       },
     *       {
     *         "url": "http://43.201.247.176/api/question?page=1", // 현재 페이지 URL
     *         "label": "1", // 현재 페이지 링크 레이블
     *         "active": true // 현재 페이지 링크 활성화 여부
     *       },
     *       {
     *         "url": null, // 다음 페이지 URL, 다음 페이지가 없으면 null
     *         "label": "Next &raquo;", // 다음 페이지 링크 레이블
     *         "active": false // 다음 페이지 링크 활성화 여부
     *       }
     *     ],
     *     "next_page_url": null, // 다음 페이지 URL
     *     "path": "http://43.201.247.176/api/question", // API 기본 URL
     *     "per_page": 10, // 페이지당 항목 수
     *     "prev_page_url": null, // 이전 페이지 URL, 이전 페이지가 없으면 null
     *     "to": 1, // 현재 페이지의 마지막 항목 번호
     *     "total": 1, // 총 항목 수
     *     "search": "" // 제목 검색어, 검색어가 없으면 빈 문자열
     *   }
     * }
     */

    public function question(Request $request) {
        return $this->fetchDataAndRespond(Question::class, ['id', 'title', 'content'], 'title', 10, $request);
    }

    /**
     * Retrieve a list of reviews.
     *
     * 고객후기 목록을 가져옵니다. 각 후기는 ID, 이미지, 필터 카테고리, 필터 지역, 제목, 내용이 포함됩니다.
     *
     * @group Reviews
     *
     * @queryParam search string 검색할 후기의 제목입니다. 검색을 하지 않을 경우 빈값( ""||null)을 입력합니다. Example: null
     * @queryParam page integer 페이지 번호입니다. 기본값은 null입니다. Example: null
     *
     * @response 200 {
     *   "success": true, // true-정상 호출 / false-호출 오류
     *   "message": "Success", // Success-정상 호출 / 게시물이 없습니다.-정상 호출, 데이터 없음 / 검색 결과가 없습니다. 검색어:-정상 호출, 검색 결과 없음 / Error-호출 오류
     *   "data": {
     *     "current_page": 1, // 현재 페이지 번호
     *     "data": [
     *       {
     *         "id": 1, // 후기 ID
     *         "image": "http://43.201.247.176/storage/images/원본.jfif", // 후기 대표사진 URL
     *         "filter_category": "원스톱 토탈서비스", // 필터 유형: 전체 정리수납/부분 정리수납/원스톱 토탈서비스
     *         "filter_area": "50평대 이상", // 필터 평수: 원룸/10평대/20평대/30평대/40평대/50평대 이상
     *         "title": "제목", // 후기 제목
     *         "content": "내용" // 후기 내용
     *       }
     *     ],
     *     "first_page_url": "http://43.201.247.176/api/review?page=1", // 첫 페이지 URL
     *     "from": 1, // 현재 페이지의 첫 번째 항목 번호
     *     "last_page": 1, // 총 페이지 수
     *     "last_page_url": "http://43.201.247.176/api/review?page=1", // 마지막 페이지 URL
     *     "links": [
     *       {
     *         "url": null, // 이전 페이지 URL, 이전 페이지가 없으면 null
     *         "label": "&laquo; Previous", // 이전 페이지 링크 레이블
     *         "active": false // 이전 페이지 링크 활성화 여부
     *       },
     *       {
     *         "url": "http://43.201.247.176/api/review?page=1", // 현재 페이지 URL
     *         "label": "1", // 현재 페이지 링크 레이블
     *         "active": true // 현재 페이지 링크 활성화 여부
     *       },
     *       {
     *         "url": null, // 다음 페이지 URL, 다음 페이지가 없으면 null
     *         "label": "Next &raquo;", // 다음 페이지 링크 레이블
     *         "active": false // 다음 페이지 링크 활성화 여부
     *       }
     *     ],
     *     "next_page_url": null, // 다음 페이지 URL
     *     "path": "http://43.201.247.176/api/review", // API 기본 URL
     *     "per_page": 10, // 페이지당 항목 수
     *     "prev_page_url": null, // 이전 페이지 URL, 이전 페이지가 없으면 null
     *     "to": 1, // 현재 페이지의 마지막 항목 번호
     *     "total": 1, // 총 항목 수
     *     "search": "" // 제목 검색어, 검색어가 없으면 빈 문자열
     *   }
     * }
     */

    public function review(Request $request) {
        return $this->fetchDataAndRespond(Review::class, ['id', 'image', 'filter_category', 'filter_area', 'title', 'content'], 'title', 10, $request);
    }

//    public function sns(Request $request) {
//        return $this->fetchDataAndRespond(Question::class, ['id', 'title', 'content'], 'title', 10, $request);
//    }

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
