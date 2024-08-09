<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use DOMDocument;
use Illuminate\Support\Facades\Log;

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
            $posts = $data['items'];

            // 결과를 배열에 추가
            $allPosts = array_merge($allPosts, $posts);
        }

        $filteredPosts = array_filter($allPosts, function($post) {
            return strpos($post['bloggerlink'], 'blog.naver.com/niceout86') !== false;
        });

        // 중복 제거
        $uniquePosts = $this->removeDuplicatePosts($filteredPosts);

        // 첫 번째 이미지 URL 추가
        $postsWithImages = $this->addFirstImageToPosts($uniquePosts);

        // 최대 20개의 게시물만 리턴
        $limitedPosts = array_slice($postsWithImages, 0, 20);

        return ApiResponse::success(array_values($limitedPosts));
    }

    // 중복 게시물 제거 함수
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

    // 첫 번째 이미지 URL 추가 함수
    private function addFirstImageToPosts($posts)
    {
        $client = new Client();

        foreach ($posts as &$post) {
                $post['firstImage'] = $this->getFirstImageFromIframe($client, $post['link']);
        }

        return $posts;
    }

    // iframe에서 첫 번째 이미지 URL을 가져오는 함수
    private function getFirstImageFromIframe($client, $iframeUrl)
    {
        try {
            $response = $client->request('GET', $iframeUrl);
            $html = $response->getBody()->getContents();

            $dom = new DOMDocument();
            @$dom->loadHTML($html);

            $images = $dom->getElementsByTagName('img');
            foreach ($images as $image) {
                $src = $image->getAttribute('src');
                if (preg_match('/^(https?:\/\/)/', $src)) {
                    return $src;
                }
            }
            return null;
        } catch (Exception $e) {
            Log::error('Image fetch failed: ' . $e->getMessage());
            return null;
        }
    }

}
