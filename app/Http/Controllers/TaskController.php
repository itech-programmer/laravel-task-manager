<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    protected TaskService $taskService;

    /**
     * Конструктор для внедрения TaskService.
     *
     * @param TaskService $taskService
     */
    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Показать список задач с пагинацией.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $sortBy = $request->get('sort', 'username');
        $direction = $request->get('direction', 'asc');

        Log::info('Получение списка задач', ['sortBy' => $sortBy, 'direction' => $direction]);

        $tasks = $this->taskService->getAllTasks($sortBy, $direction);

        Log::info('Список задач успешно получен', ['tasks' => $tasks]);

        return response()->json($tasks);
    }

    /**
     * Создать новую задачу.
     *
     * @param CreateTaskRequest $request
     * @return JsonResponse
     */
    public function store(CreateTaskRequest $request): JsonResponse
    {
        $validatedData = $request->validated();

        Log::info('Создание новой задачи', [
            'validatedData' => $validatedData,
        ]);

        $task = $this->taskService->createTask($validatedData);

        Log::info('Задача успешно создана', ['task' => $task]);

        return response()->json($task, 201);
    }

    /**
     * Обновить существующую задачу.
     *
     * @param Task $task
     * @param UpdateTaskRequest $request
     * @return JsonResponse
     */
    public function update(Task $task, UpdateTaskRequest $request): JsonResponse
    {
        $validatedData = $request->validated();
        Log::info('Обновление задачи', ['task_id' => $task->id, 'data' => $validatedData]);

        $updatedTask = $this->taskService->updateTask($task, $validatedData);

        Log::info('Задача успешно обновлена', ['task_id' => $updatedTask->id]);

        return response()->json($updatedTask);
    }

    /**
     * Завершить задачу.
     *
     * @param Task $task
     * @return JsonResponse
     */
    public function complete(Task $task): JsonResponse
    {
        Log::info('Завершение задачи', ['task_id' => $task->id]);

        $completedTask = $this->taskService->completeTask($task, Auth::id());

        Log::info('Задача успешно завершена', ['task_id' => $completedTask->id]);

        return response()->json($completedTask);
    }
}
