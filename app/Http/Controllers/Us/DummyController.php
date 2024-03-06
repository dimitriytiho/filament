<?php

namespace App\Http\Controllers\Us;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DummyController extends Controller
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

    /**
     * @param string $slug
     * @return View
     */
    public function show(string $slug): View
    {
        $item = $this->model::where('slug', $slug)
            ->where('active', true)
            ->firstOrFail();

        $title = $item->title;
        return view(
            $this->view,
            compact('title', 'item')
        );
    }
}
