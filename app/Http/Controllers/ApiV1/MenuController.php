<?php

namespace App\Http\Controllers\ApiV1;

use App\Http\Controllers\Controller;
use App\Http\Resources\MenuCollection;

class MenuController extends Controller
{
    /**
     * @return MenuCollection
     */
    public function index(): MenuCollection
    {
        $cacheTime = 60;
        $paginate = 20;
        $data = cache()->remember($this->snake, $cacheTime, function () use ($paginate) {
            return $this->model::paginate($paginate);
        });
        return new MenuCollection($data);
    }
}
