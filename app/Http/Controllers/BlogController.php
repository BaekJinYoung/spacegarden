<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    function fetchNaverBlogPosts($blogId) {
        $clientId = 'YOUR_NAVER_CLIENT_ID';  // 네이버 애플리케이션의 Client ID
        $clientSecret = 'YOUR_NAVER_CLIENT_SECRET';  // 네이버 애플리케이션의 Client Secret

        $client = new Client();

        $query = 'site:blog.naver.com/' . $blogId;

        $response = $client->request('GET', 'https://openapi.naver.com/v1/search/blog', [
            'headers' => [
                'X-Naver-Client-Id' => $clientId,
                'X-Naver-Client-Secret' => $clientSecret,
            ],
            'query' => [
                'query' => $query,
                'display' => 10, // 가져올 게시물 수
                'start' => 1, // 시작 인덱스
                'sort' => 'date', // 정렬 기준 (sim: 유사도, date: 날짜)
            ],
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['items']; // 블로그 게시물 목록
    }

    public function showBlogPosts(Request $request)
    {
        $blogId = $request->input('blogId'); // 쿼리 파라미터에서 블로그 ID를 가져옵니다.

        if (empty($blogId)) {
            return response()->json(['error' => '블로그 ID를 입력해 주세요.'], 400);
        }

        $posts = $this->fetchNaverBlogPosts($blogId);

        return response()->json($posts);
    }
}
