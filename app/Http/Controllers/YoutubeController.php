<?php

namespace App\Http\Controllers;

use App\Http\Resources\ApiResponse;
use App\Services\YoutubeService;
use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    protected $YoutubeService;

    public function __construct(YoutubeService $YoutubeService)
    {
        $this->YoutubeService = $YoutubeService;
    }

    public function index()
    {
        $channelId = 'UC_x5XG1OV2P6uZZ5FSM9Ttw'; // Google Developers 채널 ID
        $videos = $this->YoutubeService->getVideosByChannelId($channelId);

        return ApiResponse::success($videos);
    }
}
