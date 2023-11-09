<?php

namespace Database\Factories;

use App\Models\MenuName;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Для запуска в консоли:
 * php artisan tinker
 * \App\Models\Menu::factory(40)->create()
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $menuName = MenuName::firstOrCreate(
            [
                'name' => 'Footer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        );

        return [
            'menu_name_id' => $menuName->id,
            'title' => $this->faker->word,
            'link' => $this->faker->url,
            'image' => $this->faker->url,
            'active' => $this->faker->boolean,
            'sort' => $this->faker->numberBetween(0, 65535),
        ];
    }
}
