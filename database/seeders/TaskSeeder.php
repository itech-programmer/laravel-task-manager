<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tasks = [
            [
                'username' => 'John Doe',
                'email' => 'john@example.com',
                'task_text' => 'Complete the project report.',
                'status' => false,
                'is_edited_by_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Jane Smith',
                'email' => 'jane@example.com',
                'task_text' => 'Prepare for the team meeting.',
                'status' => true,
                'is_edited_by_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'task_text' => 'Write unit tests for the new feature.',
                'status' => false,
                'is_edited_by_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Bob Brown',
                'email' => 'bob@example.com',
                'task_text' => 'Fix bugs reported by QA.',
                'status' => true,
                'is_edited_by_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Charlie Davis',
                'email' => 'charlie@example.com',
                'task_text' => 'Update documentation for the API.',
                'status' => false,
                'is_edited_by_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Diana Evans',
                'email' => 'diana@example.com',
                'task_text' => 'Create a presentation for the client meeting.',
                'status' => true,
                'is_edited_by_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Eve Foster',
                'email' => 'eve@example.com',
                'task_text' => 'Conduct market research for the new product.',
                'status' => false,
                'is_edited_by_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Frank Green',
                'email' => 'frank@example.com',
                'task_text' => 'Set up the staging environment.',
                'status' => true,
                'is_edited_by_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Grace Hall',
                'email' => 'grace@example.com',
                'task_text' => 'Review pull requests from the team.',
                'status' => false,
                'is_edited_by_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Hank Irwin',
                'email' => 'hank@example.com',
                'task_text' => 'Plan the next sprint and assign tasks.',
                'status' => true,
                'is_edited_by_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Ivy Johnson',
                'email' => 'ivy@example.com',
                'task_text' => 'Collect feedback from the team on the new feature.',
                'status' => false,
                'is_edited_by_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Jack King',
                'email' => 'jack@example.com',
                'task_text' => 'Prepare a budget report for the project.',
                'status' => true,
                'is_edited_by_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Kathy Lee',
                'email' => 'kathy@example.com',
                'task_text' => 'Schedule a team outing for next month.',
                'status' => false,
                'is_edited_by_admin' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'Leo Martin',
                'email' => 'leo@example.com',
                'task_text' => 'Conduct a code review for the latest changes.',
                'status' => true,
                'is_edited_by_admin' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Добавьте больше задач по необходимости
        ];

        // Вставка данных в таблицу tasks
        Task::insert($tasks);
    }
}
