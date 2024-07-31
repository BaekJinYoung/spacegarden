<?php

namespace App\Http\Controllers\admin;

use App\Models\Announcement;
use App\Services\ValidationService;

class AnnouncementController extends BaseController
{
    public function __construct(Announcement $announcement, ValidationService $validationService) {
        parent::__construct($announcement, $validationService);
        $this->setDefaultPerPage(10);
    }

    protected function getValidationContext(): string {
        return 'announcement';
    }
}
