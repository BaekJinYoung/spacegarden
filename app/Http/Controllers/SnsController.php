<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class SnsController extends Controller
{
    private $instagramAccessToken;
    private $naverClientId;
    private $naverClientSecret;

    public function __construct()
    {
        $this->instagramAccessToken = env('INSTAGRAM_ACCESS_TOKEN');
        $this->naverClientId = 'zq6e8lRLTCQqCVh1UfGt';
        $this->naverClientSecret = '9okmubdb5x';
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
     *         "id": "18099532147430854", // 미디어 ID
     *         "permalink": "https://www.instagram.com/p/C-bh0bTz1hu/", // 인스타그램 게시물 URL
     *         "media_type": "CAROUSEL_ALBUM", // 미디어 유형 (IMAGE, VIDEO, CAROUSEL_ALBUM)
     *         "media_url": "https://scontent-nrt1-2.cdninstagram.com/v/t51.29350-15/454639965_515905504308983_1690389984879416330_n.heic", // 미디어 URL
     *         "thumbnail_url": "https://scontent-nrt1-2.cdninstagram.com/v/t51.29350-15/454724988_1035530854834035_7451642132034069594_n.jpg" // 비디오 썸네일 URL (VIDEO에만 존재)
     *       }
     *     ],
     *     "blog_posts": [
     *       {
     *         "title": "Example Blog Post Title", // 블로그 게시물 제목
     *         "link": "https://blog.naver.com/example", // 블로그 게시물 URL
     *         "description": "Example blog post description.", // 블로그 게시물 설명
     *         "bloggerlink": "blog.naver.com/niceout86" // 블로거 링크
     *       }
     *     ]
     *   }
     * }
     */

    public function getSnsPosts()
    {
        $client = new Client();

        try {
            // Fetch Instagram posts
            $instagramUserId = '8906428979373592';
            $limit = 20;
            $instagramUrl = "https://graph.instagram.com/$instagramUserId/media?fields=id,permalink,media_type,media_url,thumbnail_url&limit=$limit&access_token=$this->instagramAccessToken";
            $instagramResponse = $client->request('GET', $instagramUrl);
            $instagramData = json_decode($instagramResponse->getBody(), true);
            $instagramPosts = $instagramData['data'];

            // Fetch Naver blog posts
            $query = '"공간정리연구소"';
            $blogResponse = $client->request('GET', 'https://openapi.naver.com/v1/search/blog', [
                'headers' => [
                    'X-Naver-Client-Id' => $this->naverClientId,
                    'X-Naver-Client-Secret' => $this->naverClientSecret,
                ],
                'query' => [
                    'query' => $query,
                    'display' => 20,
                    'start' => 1,
                    'sort' => 'date',
                ],
            ]);
            $blogData = json_decode($blogResponse->getBody(), true);

            // Filter blog posts
            $filteredBlogPosts = array_filter($blogData['items'], function ($post) {
                return $post['bloggerlink'] === 'blog.naver.com/niceout86';
            });

            return ApiResponse::success([
                'instagram_posts' => $instagramPosts,
                'blog_posts' => array_values($filteredBlogPosts),
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
