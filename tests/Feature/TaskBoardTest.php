<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskBoardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->artisan('migrate');
    }

    public function test_tasks_route_is_accessible()
    {
        $board = Board::factory()->create(['is_default' => true]);
        
        $response = $this->get('/tasks');
        $response->assertStatus(200);
    }

    public function test_shared_board_route_is_accessible()
    {
        $board = Board::factory()->create();
        
        $response = $this->get('/tasks/' . $board->share_token);
        $response->assertStatus(200);
    }

    public function test_task_can_be_updated()
    {
        $board = Board::factory()->create(['is_default' => true]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'status' => 'todo',
            'position' => 0,
        ]);

        $response = $this->post("/tasks/{$task->id}/update", [
            'status' => 'in_progress',
            'position' => 0,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'in_progress',
        ]);
    }

    public function test_task_update_validates_status()
    {
        $board = Board::factory()->create(['is_default' => true]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'status' => 'todo',
            'position' => 0,
        ]);

        $response = $this->post("/tasks/{$task->id}/update", [
            'status' => 'invalid_status',
            'position' => 0,
        ]);

        $response->assertStatus(302);
    }
}
