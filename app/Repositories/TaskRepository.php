<?php

namespace App\Repositories;

use App\Contracts\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * Получить все задачи с сортировкой.
     *
     * @param string $sortBy
     * @param string $direction
     * @return mixed
     */
    public function getAllTasks(string $sortBy, string $direction)
    {
        Log::info('Запрос задач из базы данных', ['sortBy' => $sortBy]);
        return Task::query()->orderBy($sortBy, $direction)->paginate(3);
    }

    /**
     * Создать новую задачу.
     *
     * @param array $data
     * @return Task
     */
    public function createTask(array $data): Task
    {
        Log::info('Создание задачи в базе данных', ['data' => $data]);

        return Task::create($data);
    }

    /**
     * Обновить существующую задачу.
     *
     * @param Task $task
     * @param array $data
     * @return Task
     */
    public function updateTask(Task $task, array $data): Task
    {
        Log::info('Обновление задачи', ['task_id' => $task->id, 'data' => $data]);

        $task->update($data);
        $task->is_edited_by_admin = true;
        $task->save();

        Log::info('Задача успешно обновлена', ['task_id' => $task->id]);

        return $task;
    }

    /**
     * Завершить задачу.
     *
     * @param Task $task
     * @return Task
     */
    public function completeTask(Task $task): Task
    {
        Log::info('Завершение задачи', ['task_id' => $task->id]);

        $task->status = true;
        $task->save();

        Log::info('Задача успешно завершена', ['task_id' => $task->id]);

        return $task;
    }
}
