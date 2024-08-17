<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    //public static $wrap = ''; // Обвёртка ответа

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'menu_name' => $this->menuName->name,
            'title' => $this->title,
            'link' => $this->link,
        ];
        //return parent::toArray($request);
    }
}
