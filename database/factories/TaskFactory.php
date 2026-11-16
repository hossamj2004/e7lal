<?php

namespace Database\Factories;

use App\Models\Board;
use App\Models\BoardColumn;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        
        return [
            'board_id' => $board->id,
            'column_id' => $column->id,
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'position' => fake()->numberBetween(0, 10),
        ];
    }
}
