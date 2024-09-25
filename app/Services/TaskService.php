<?php

namespace App\Services;

use App\Contracts\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class TaskService
{
    /**
     * Получить все задачи с сортировкой.
     *
     * @param string $sortBy Поле для сортировки
     * @param string $direction Направление сортировки (asc/desc)
     * @return mixed
     */
    public function getAllTasks(string $sortBy, string $direction)
    {
        Log::info('Вызов репозитория задач для получения задач', [
            'sortBy' => $sortBy,
            'direction' => $direction
        ]);

        return Task::query()->orderBy($sortBy, $direction)->paginate(3);
    }

    /**
     * Создать новую задачу.
     *
     * @param array $data Данные для создания задачи
     * @return Task
     */
    public function createTask(array $data): Task
    {
        Log::info('Вызов репозитория для создания задачи', [
            'data' => $data,
        ]);

          return Task::query()->create($data);
    }

    /**
     * Обновить существующую задачу.
     *
     * @param int $taskId Задача для обновления
     * @param array $data Данные для обновления задачи
     */
    public function updateTask(int $taskId, array $data)
    {
        Log::info('Обновление задачи', [
            'task_id' => $taskId,
            'data' => $data
        ]);

        $task = Task::query()->findOrFail($taskId);

        $task->update(array_merge($data, ['is_edited_by_admin' => true]));
        return $task;
    }

    /**
     * Завершить задачу.
     *
     * @param int $task Задача для завершения
     */
    public function completeTask(int $taskId)
    {
        Log::info('Завершение задачи', [
            'task_id' => $taskId,
        ]);
        $task = Task::query()->findOrFail($taskId);
        $task->update(['status' => true]);
        return $task;
    }
}
