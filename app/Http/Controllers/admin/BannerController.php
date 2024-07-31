<?php

namespace App\Http\Controllers\admin;

use App\Models\Banner;

class BannerController extends BaseController
{
    public function __construct(Banner $banner) {
        parent::__construct($banner);
        $this->setDefaultPerPage(10);
    }
}
