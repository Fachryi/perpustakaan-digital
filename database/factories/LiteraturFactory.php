<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Literatur>
 */
class LiteraturFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'judul' => fake()->paragraph(1),
            'abstrak' => fake()->paragraph(5),
            'keyword' => "Lorem, ipsum, dolom",
            'jenis_id' => 1,
            'file' => 'tester.pdf'
        ];
    }
}
