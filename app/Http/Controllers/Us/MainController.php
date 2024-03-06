<?php

namespace App\Http\Controllers\Us;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class MainController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        $title = __('Main_page');
        return view(
            $this->view,
            compact('title')
        );
    }
}
