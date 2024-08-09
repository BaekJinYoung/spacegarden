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
        $client = new Client();

        $instagramUserId = '8906428979373592';

        $url = "https://graph.instagram.com/$instagramUserId/media?fields=permalink,media_type,media_url,thumbnail_url&access_token=$this->accessToken";

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
