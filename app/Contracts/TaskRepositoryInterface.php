<?php

namespace App\Contracts;

use App\Models\Task;

interface TaskRepositoryInterface
{
    public function getAllTasks(string $sortBy, string $direction);
    public function createTask(array $data): Task;
    public function updateTask(Task $task, array $data): Task;
    public function completeTask(Task $task): Task;
}
