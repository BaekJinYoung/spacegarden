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

    public function getInstagramPosts()
    {
        // Guzzle 클라이언트를 사용하여 API 요청
        $client = new Client();

        // 인스타그램 사용자 ID (사용자의 ID를 얻기 위해 'me?fields=id' 엔드포인트를 호출합니다.)
        $instagramUserId = 'INSTAGRAM_USER_ID';

        // API 요청 URL
        $url = "https://graph.instagram.com/{$instagramUserId}/media?fields=id,caption,media_type,media_url,permalink,thumbnail_url,timestamp&access_token={$this->accessToken}";

        try {
            $response = $client->request('GET', $url);
            $data = json_decode($response->getBody(), true);

            $posts = $data['data'];

            return ApiResponse::success($posts);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
