<?php

namespace App\Http\Controllers\admin;

use App\Http\Requests\PopupRequest;
use App\Models\Popup;
use App\Services\ValidationService;

class PopupController extends BaseController
{
    public function __construct(Popup $popup, ValidationService $validationService) {
        parent::__construct($popup, $validationService);
        $this->setDefaultPerPage(10);
    }

    protected function getValidationContext(): string {
        return 'popup';
    }

}
