<?php

namespace App\Http\Controllers\admin;

use App\Models\Question;

class QuestionController extends BaseController
{
    public function __construct(Question $question) {
        parent::__construct($question);
        $this->setDefaultPerPage(10);
    }
}
