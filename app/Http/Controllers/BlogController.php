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

        $keywords = ['이정원 공간정원', '토탈홈케어'];
        $allPosts = [];

        foreach ($keywords as $keyword) {
            $response = $client->request('GET', 'https://openapi.naver.com/v1/search/blog', [
                'headers' => [
                    'X-Naver-Client-Id' => $clientId,
                    'X-Naver-Client-Secret' => $clientSecret,
                ],
                'query' => [
                    'query' => $keyword,
                    'display' => 20, // 가져올 게시물 수
                    'start' => 1, // 시작 인덱스
                    'sort' => 'date', // 정렬 기준 (sim: 유사도, date: 날짜)
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $posts = $data['items'];

            // 결과를 배열에 추가
            $allPosts = array_merge($allPosts, $posts);
        }

        // 중복 제거
        $uniquePosts = $this->removeDuplicatePosts($allPosts);

        return ApiResponse::success(array_values($uniquePosts));
    }

    // 중복 게시물 제거 함수
    private function removeDuplicatePosts($posts) {
        $unique = [];
        $seen = [];

        foreach ($posts as $post) {
            $link = $post['link'];
            if (!in_array($link, $seen)) {
                $seen[] = $link;
                $unique[] = $post;
            }
        }

        return $unique;
    }
}
