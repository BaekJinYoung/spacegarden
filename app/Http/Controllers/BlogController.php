<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use GuzzleHttp\Client;
use Illuminate\Http\Client\RequestException;

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
            try {
                $response = $client->request('GET', 'https://openapi.naver.com/v1/search/blog', [
                    'headers' => [
                        'X-Naver-Client-Id' => $clientId,
                        'X-Naver-Client-Secret' => $clientSecret,
                    ],
                    'query' => [
                        'query' => $keyword,
                        'display' => 30, // 가져올 게시물 수
                        'start' => 1, // 시작 인덱스
                        'sort' => 'date', // 정렬 기준 (sim: 유사도, date: 날짜)
                    ],
                ]);

                $data = json_decode($response->getBody(), true);
                $posts = $data['items'];

                // 각 게시물의 본문에서 이미지를 추출
                foreach ($posts as &$post) {
                    $post['title'] = $this->removeBoldTags($post['title']);
                    // 이미지 검색을 위한 제목 캐싱
                    if (!isset($post['images'])) {
                        $post['images'] = $this->searchImages($post['title'], $clientId, $clientSecret);
                    }
                    $post['description'] = $this->removeBoldTags($post['description']);
                }

                // 결과를 배열에 추가
                $allPosts = array_merge($allPosts, $posts);

                // API 호출 사이에 지연 추가
                sleep(2); // 1초 대기
            } catch (RequestException $e) {
                // 예외 처리 로직 추가 (로그 기록 등)
                continue;
            }
        }

        $filteredPosts = array_filter($allPosts, function($post) {
            return strpos($post['bloggerlink'], 'blog.naver.com/niceout86') !== false;
        });

        // 중복 제거
        $uniquePosts = $this->removeDuplicatePosts($filteredPosts);

        // 최대 20개의 게시물만 리턴
        $limitedPosts = array_slice($uniquePosts, 0, 20);

        return ApiResponse::success(array_values($limitedPosts));
    }

    private function searchImages($query, $clientId, $clientSecret)
    {
        $client = $this->client;
        $retryCount = 0;
        $maxRetries = 3; // 최대 재시도 횟수
        $waitTime = 2; // 재시도 간 대기 시간 (초)

        while ($retryCount < $maxRetries) {
            try {
                $response = $client->request('GET', 'https://openapi.naver.com/v1/search/image', [
                    'headers' => [
                        'X-Naver-Client-Id' => $clientId,
                        'X-Naver-Client-Secret' => $clientSecret,
                    ],
                    'query' => [
                        'query' => $query,
                        'display' => 1, // 이미지 검색 결과를 1개만 가져옴
                    ],
                ]);

                $data = json_decode($response->getBody(), true);
                return $data['items'][0]['link'] ?? null; // 이미지 링크가 없으면 null 반환
            } catch (\GuzzleHttp\Exception\ClientException $e) {
                if ($e->getResponse()->getStatusCode() == 429) {
                    // 429 오류 발생 시 재시도
                    $retryCount++;
                    sleep($waitTime); // 대기 시간
                } else {
                    // 다른 오류 발생 시 예외를 다시 던짐
                    throw $e;
                }
            }
        }

        return null;
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

    private function removeBoldTags($text)
    {
        return preg_replace('/<b>(.*?)<\/b>/i', '$1', $text);
    }
}
