<?php

namespace Database\Factories;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskCommentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'body' => $this->faker->sentence(),
            'author_name' => $this->faker->optional()->name(),
        ];
    }
}
