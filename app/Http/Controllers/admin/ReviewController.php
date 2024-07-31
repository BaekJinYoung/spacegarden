<?php

namespace App\Http\Controllers\admin;

use App\Models\Review;

class ReviewController extends BaseController
{
    public function __construct(Review $review) {
        parent::__construct($review);
        $this->setDefaultPerPage(10);
    }
}
