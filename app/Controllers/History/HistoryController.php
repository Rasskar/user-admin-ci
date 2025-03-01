<?php

namespace App\Controllers\History;

use App\Controllers\BaseController;

class HistoryController extends BaseController
{
    public function index(): string
    {
        return view('history/index');
    }
}