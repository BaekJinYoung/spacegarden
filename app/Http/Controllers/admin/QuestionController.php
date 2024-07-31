<?php

namespace App\Http\Controllers\admin;

use App\Models\Question;
use App\Services\ValidationService;

class QuestionController extends BaseController
{
    public function __construct(Question $question, ValidationService $validationService) {
        parent::__construct($question, $validationService);
        $this->setDefaultPerPage(10);
    }

    protected function getValidationContext(): string {
        return 'question';
    }
}
