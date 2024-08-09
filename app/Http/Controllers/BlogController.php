<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;

class BlogController extends Controller
{
    protected $client; // Guzzle Client 인스턴스를 위한 속성 선언

    // 생성자에서 Guzzle Client 주입
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getBlogPosts()
    {
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
                    'display' => 50, // 가져올 게시물 수
                    'start' => 1, // 시작 인덱스
                    'sort' => 'date', // 정렬 기준 (sim: 유사도, date: 날짜)
                ],
            ]);

            $data = json_decode($response->getBody(), true);
            $posts = array_map(function($post) {
                unset($post['bloggername']);
                unset($post['bloggerlink']);
                return $post;
            }, $data['items']);

            foreach ($posts as &$post) {
                $post['title'] = $this->removeBoldTags($post['title']);
                $post['description'] = $this->removeBoldTags($post['description']);
                $post['postdate_formatted'] = Carbon::parse($post['postdate'])->format('Y-m-d');
                unset($post['postdate']);
            }

            $allPosts = array_merge($allPosts, $posts);
        }

        $filteredPosts = array_filter($allPosts, function($post) {
            return strpos($post['bloggerlink'], 'blog.naver.com/niceout86') !== false;
        });

        $uniquePosts = $this->removeDuplicatePosts($filteredPosts);
        $limitedPosts = array_slice($uniquePosts, 0, 20);

        return ApiResponse::success(array_values($limitedPosts));
    }

    private function removeDuplicatePosts($posts)
    {
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

    private function removeBoldTags($text)
    {
        return preg_replace('/<b>(.*?)<\/b>/i', '$1', $text);
    }
}
