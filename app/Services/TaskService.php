<?php

namespace App\Services;

use App\Contracts\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Support\Facades\Log;

class TaskService
{
    protected TaskRepositoryInterface $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

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

        return $this->taskRepository->getAllTasks($sortBy, $direction);
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

        return $this->taskRepository->createTask($data);
    }

    /**
     * Обновить существующую задачу.
     *
     * @param Task $task Задача для обновления
     * @param array $data Данные для обновления задачи
     * @return Task
     */
    public function updateTask(Task $task, array $data): Task
    {
        Log::info('Обновление задачи', [
            'task_id' => $task->id,
            'data' => $data
        ]);

        return $this->taskRepository->updateTask($task, $data);
    }

    /**
     * Завершить задачу.
     *
     * @param Task $task Задача для завершения
     * @param int $userId Идентификатор пользователя, который завершает задачу
     * @return Task
     */
    public function completeTask(Task $task, int $userId): Task
    {
        Log::info('Завершение задачи', [
            'task_id' => $task->id,
            'completed_by' => $userId
        ]);

        return $this->taskRepository->completeTask($task);
    }
}
