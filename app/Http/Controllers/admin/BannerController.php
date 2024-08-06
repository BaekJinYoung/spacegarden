<?php

namespace App\Http\Controllers\admin;

use App\Models\Banner;
use App\Services\ValidationService;

class BannerController extends BaseController
{
    public function __construct(Banner $banner, ValidationService $validationService) {
        parent::__construct($banner, $validationService);
        $this->setDefaultPerPage(0);
    }

    protected function getValidationContext(): string {
        return 'banner';
    }
}
