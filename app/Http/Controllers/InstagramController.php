<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class InstagramController extends Controller
{
    private $accessToken;

    public function __construct()
    {
        $this->accessToken = env('INSTAGRAM_ACCESS_TOKEN');
    }

    /**
     * 인스타그램
     *
     * Instagram 미디어 목록을 가져옵니다. 각 미디어 항목은 ID, permalink, media_type, media_url을 포함합니다.
     *
     * @group Instagram
     *
     * @queryParam access_token string 액세스 토큰입니다. Example: null
     * @queryParam user_id string Instagram 사용자 ID입니다. Example: null
     *
     * @response 200 {
     *   "success": true, // true-정상 호출 / false-호출 오류
     *   "message": "Success", // Success-정상 호출 / Error-호출 오류
     *   "data": [
     *     {
     *       "id": "18099532147430854", // 미디어 ID
     *       "permalink": "https://www.instagram.com/p/C-bh0bTz1hu/", // 인스타그램 게시물 URL
     *       "media_type": "CAROUSEL_ALBUM", // 미디어 유형 (IMAGE, VIDEO, CAROUSEL_ALBUM)
     *       "media_url": "https://scontent-nrt1-2.cdninstagram.com/v/t51.29350-15/454639965_515905504308983_1690389984879416330_n.heic", // 미디어 URL
     *       "thumbnail_url": "https://scontent-nrt1-2.cdninstagram.com/v/t51.29350-15/454724988_1035530854834035_7451642132034069594_n.jpg" // 비디오 썸네일 URL (VIDEO에만 존재)
     *     }
     *     }
     *   ]
     * }
     */

    public function getInstagramPosts()
    {
        $client = new Client();

        $instagramUserId = '8906428979373592';

        $limit = 20;

        $url = "https://graph.instagram.com/$instagramUserId/media?fields=id,permalink,media_type,media_url,thumbnail_url&limit=$limit&access_token=$this->accessToken";

        try {
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody(), true);

            $posts = array_map(function($post) {
                unset($post['id']);
                return $post;
            }, $data['data']);

            return ApiResponse::success($posts);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
