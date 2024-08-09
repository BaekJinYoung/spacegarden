<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    function getBlogPosts() {
        $client = new Client();

        $clientId = 'zq6e8lRLTCQqCVh1UfGt';  // 네이버 애플리케이션의 Client ID
        $clientSecret = '9okmubdb5x';  // 네이버 애플리케이션의 Client Secret

        $query = 'niceout86';

        $response = $client->request('GET', 'https://openapi.naver.com/v1/search/blog', [
            'headers' => [
                'X-Naver-Client-Id' => $clientId,
                'X-Naver-Client-Secret' => $clientSecret,
            ],
            'query' => [
                'query' => $query,
                'display' => 20, // 가져올 게시물 수
                'start' => 1, // 시작 인덱스
                'sort' => 'date', // 정렬 기준 (sim: 유사도, date: 날짜)
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        $posts = $data['items'];

        return ApiResponse::success($posts);
    }
}
