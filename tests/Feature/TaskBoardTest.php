<?php

namespace Tests\Feature;

use App\Models\Board;
use App\Models\BoardColumn;
use App\Models\Task;
use App\Models\TaskComment;
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

    public function test_guest_can_add_column()
    {
        $board = Board::factory()->create();

        $response = $this->post("/boards/{$board->id}/columns", [
            'name' => 'New Column',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('board_columns', [
            'board_id' => $board->id,
            'name' => 'New Column',
        ]);
    }

    public function test_guest_can_add_task()
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);

        $response = $this->post("/columns/{$column->id}/tasks", [
            'title' => 'New Task',
            'description' => 'Task description',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseHas('tasks', [
            'column_id' => $column->id,
            'title' => 'New Task',
        ]);
    }

    public function test_guest_can_delete_task()
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);

        $response = $this->delete("/tasks/{$task->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
        
        $this->assertDatabaseMissing('tasks', [
            'id' => $task->id,
        ]);
    }

    public function test_cannot_delete_non_empty_column()
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);

        $response = $this->delete("/columns/{$column->id}");

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_can_view_task_with_comments()
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);

        TaskComment::factory()->create([
            'task_id' => $task->id,
            'body' => 'Test comment',
            'author_name' => 'Tester',
        ]);

        $response = $this->get("/tasks/{$task->id}/comments");

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'task' => [
                'id' => $task->id,
                'title' => $task->title,
                'comments_count' => 1,
            ],
        ]);
        $response->assertJsonPath('comments.0.body', 'Test comment');
        $response->assertJsonPath('comments.0.author_name', 'Tester');
    }

    public function test_can_add_comment_to_task()
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);

        $response = $this->post("/tasks/{$task->id}/comments", [
            'body' => 'New comment text',
            'author_name' => 'Test User',
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('task_comments', [
            'task_id' => $task->id,
            'body' => 'New comment text',
            'author_name' => 'Test User',
        ]);
    }

    public function test_cannot_add_empty_comment()
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);

        $response = $this->post("/tasks/{$task->id}/comments", [
            'body' => '',
        ]);

        $response->assertStatus(302);
    }

    public function test_can_delete_comment()
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);

        $comment = TaskComment::factory()->create([
            'task_id' => $task->id,
        ]);

        $response = $this->delete("/comments/{$comment->id}");

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseMissing('task_comments', [
            'id' => $comment->id,
        ]);
    }

    public function test_can_block_task_with_reason()
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
            'is_blocked' => false,
        ]);

        $response = $this->post("/tasks/{$task->id}/blocked", [
            'is_blocked' => true,
            'blocked_reason' => 'Waiting for approval',
            'author_name' => 'Test User',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'task' => [
                'is_blocked' => true,
                'blocked_reason' => 'Waiting for approval',
            ],
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'is_blocked' => true,
            'blocked_reason' => 'Waiting for approval',
        ]);

        // Auto-comment should be created
        $this->assertDatabaseHas('task_comments', [
            'task_id' => $task->id,
        ]);
    }

    public function test_cannot_block_task_without_reason()
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);

        $response = $this->post("/tasks/{$task->id}/blocked", [
            'is_blocked' => true,
            'blocked_reason' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }

    public function test_can_unblock_task()
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
            'is_blocked' => true,
            'blocked_reason' => 'Was blocked',
        ]);

        $response = $this->post("/tasks/{$task->id}/blocked", [
            'is_blocked' => false,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'task' => [
                'is_blocked' => false,
            ],
        ]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'is_blocked' => false,
        ]);
    }

    public function test_comments_deleted_when_task_deleted()
    {
        $board = Board::factory()->create();
        $column = BoardColumn::factory()->create(['board_id' => $board->id]);
        $task = Task::factory()->create([
            'board_id' => $board->id,
            'column_id' => $column->id,
        ]);

        $comment = TaskComment::factory()->create([
            'task_id' => $task->id,
        ]);

        $this->delete("/tasks/{$task->id}");

        $this->assertDatabaseMissing('task_comments', [
            'id' => $comment->id,
        ]);
    }
}
