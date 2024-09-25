@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <h1 class="text-center">Task Manager</h1>

        <form id="user-form" class="mb-4">
            <div class="form-group">
                <label for="username">Имя пользователя</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
        </form>

        <form id="task-form" class="mb-4">
            <div class="form-group">
                <label for="task_text">Текст задачи</label>
                <input type="text" class="form-control" id="task_text" name="task_text" required>
            </div>
            <button type="submit" class="btn btn-primary">Добавить задачу</button>
        </form>

        <div id="task-list" class="list-group">
            <!-- Задачи будут добавлены здесь динамически -->
        </div>

        <div id="pagination" class="mt-3">
            <!-- Пагинация будет добавлена здесь динамически -->
        </div>
    </div>
@endsection
