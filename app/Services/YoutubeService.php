<?php

namespace App\Services;

use GuzzleHttp\Client;

class YoutubeService
{
    protected $apiKey;
    protected $client;

    public function __construct()
    {
        $this->apiKey = env('YOUTUBE_API_KEY');
        $this->client = new Client([
            'base_uri' => 'https://www.googleapis.com/youtube/v3/'
        ]);
    }

    public function getVideosByChannelId($channelId)
    {
        $response = $this->client->get('search', [
            'query' => [
                'key' => $this->apiKey,
                'channelId' => $channelId,
                'part' => 'snippet',
                'order' => 'date',
                'maxResults' => 10
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        return $data['items'] ?? [];
    }
}
