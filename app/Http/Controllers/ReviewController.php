<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends BaseController
{
    public function __construct(Review $review) {
        parent::__construct($review);
        $this->setDefaultPerPage(10);
    }
}
