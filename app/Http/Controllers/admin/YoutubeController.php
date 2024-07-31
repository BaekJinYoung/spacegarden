<?php

namespace App\Http\Controllers\admin;

use App\Models\Youtube;
use App\Services\ValidationService;

class YoutubeController extends BaseController
{
    public function __construct(Youtube $youtube, ValidationService $validationService) {
        parent::__construct($youtube, $validationService);
        $this->setDefaultPerPage(8);
    }

    protected function getValidationContext(): string {
        return 'youtube';
    }
}
