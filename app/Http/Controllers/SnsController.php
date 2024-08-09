<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SnsController extends Controller
{
    private $instagramController;
    private $blogController;

    public function __construct(InstagramController $instagramController, BlogController $blogController)
    {
        $this->instagramController = $instagramController;
        $this->blogController = $blogController;
    }

    /**
     * SNS
     *
     * Instagram 미디어 목록과 네이버 블로그 게시물을 가져옵니다.
     *
     * @group SNS
     *
     * @response 200 {
     *   "success": true, // true-정상 호출 / false-호출 오류
     *   "message": "Success", // Success-정상 호출 / Error-호출 오류
     *   "data": {
     *     "instagram_posts": [
     *       {
     *         "permalink": "https://www.instagram.com/p/C-bh0bTz1hu/", // 인스타그램 게시물 URL
     *         "media_type": "CAROUSEL_ALBUM", // 미디어 유형 (IMAGE, VIDEO, CAROUSEL_ALBUM)
     *         "media_url": "https://scontent-nrt1-2.cdninstagram.com/v/t51.29350-15/454639965_515905504308983_1690389984879416330_n.heic", // 미디어 URL
     *         "thumbnail_url": "https://scontent-nrt1-2.cdninstagram.com/v/t51.29350-15/454724988_1035530854834035_7451642132034069594_n.jpg" // 비디오 썸네일 URL (VIDEO에만 존재)
     *       }
     *     ],
     *     "blog_posts": [
     *       {
     *         "title": "주부 9단 만드는 체계적인 강서정리수납", // 블로그 게시물 제목
     *         "link": "https://blog.naver.com/niceout86/223541899713", // 블로그 게시물 URL
     *         "description": "그렇다면 공간정원의 정리수납 컨설팅을 통해 지금보다 더욱 넓고 쾌적한 공간에서 생활을 시작해 보세요! 감사합니다:) 삶이 바뀌는 마법같은 정리 이야기 국내 최고의 정리전문기업 【이정원... ", // 블로그 게시물 요약
     *         "postdate_formatted": "2024-08-09" // 게시물 작성일, 'Y-m-d' 형식
     *       }
     *     ]
     *   }
     * }
     */

    public function getSnsPosts()
    {
        try {
            // Fetch Instagram posts
            $instagramResponse = $this->instagramController->getInstagramPosts();
            $instagramPosts = $instagramResponse->getData(true)['data'];

            // Fetch Naver blog posts
            $blogResponse = $this->blogController->getBlogPosts();
            $blogPosts = $blogResponse->getData(true)['data'];

            return ApiResponse::success([
                'instagram_posts' => $instagramPosts,
                'blog_posts' => $blogPosts,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
