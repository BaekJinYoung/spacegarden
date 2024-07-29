<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class AnnouncementController extends BaseController
{
    public function __construct(Announcement $announcement) {
        parent::__construct($announcement);
        $this->setDefaultPerPage(10);
    }
}
