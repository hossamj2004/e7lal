<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\BoardColumn;
use App\Models\Task;
use App\Models\User;
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
        BoardColumn::factory()->create(['board_id' => $board->id]);
        
        $response = $this->get('/tasks');
        $response->assertStatus(200);
    }

    public function test_shared_board_route_is_accessible()
    {
        $board = Board::factory()->create();
        BoardColumn::factory()->create(['board_id' => $board->id]);
        
        $response = $this->get('/tasks/' . $board->share_token);
        $response->assertStatus(200);
    }

    public function test_task_can_be_updated()
    {
        $board = Board::factory()->create(['is_default' => true]);
        $column1 = BoardColumn::factory()->create(['board_id' => $board->id, 'position' => 0]);
        $column2 = BoardColumn::factory()->create(['board_id' => $board->id, 'position' => 1]);
        
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column1->id,
            'position' => 0,
        ]);

        $response = $this->post("/tasks/{$task->id}/update", [
            'column_id' => $column2->id,
            'position' => 0,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'column_id' => $column2->id,
        ]);
    }

    public function test_task_update_validates_column()
    {
        $board = Board::factory()->create(['is_default' => true]);
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
            'position' => 0,
        ]);

        $response = $this->post("/tasks/{$task->id}/update", [
            'column_id' => 99999,
            'position' => 0,
        ]);

        $response->assertStatus(302);
    }

    public function test_task_cannot_move_to_different_board_column()
    {
        $board1 = Board::factory()->create();
        $board2 = Board::factory()->create();
        $column1 = BoardColumn::factory()->create(['board_id' => $board1->id]);
        $column2 = BoardColumn::factory()->create(['board_id' => $board2->id]);
        
        $task = Task::factory()->create([
            'board_id' => $board1->id,
            'column_id' => $column1->id,
            'position' => 0,
        ]);

        $response = $this->post("/tasks/{$task->id}/update", [
            'column_id' => $column2->id,
            'position' => 0,
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_admin_can_add_column()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $board = Board::factory()->create();

        $response = $this->actingAs($admin)->post("/boards/{$board->id}/columns", [
            'name' => 'New Column',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('board_columns', [
            'board_id' => $board->id,
            'name' => 'New Column',
        ]);
    }

    public function test_guest_cannot_add_column()
    {
        $board = Board::factory()->create();

        $response = $this->post("/boards/{$board->id}/columns", [
            'name' => 'New Column',
        ]);

        $response->assertStatus(403);
    }
}
