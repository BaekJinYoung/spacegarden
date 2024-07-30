<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends BaseController
{
    public function __construct(Question $question) {
        parent::__construct($question);
        $this->setDefaultPerPage(10);
    }
}
