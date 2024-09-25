@extends('layouts.app')

@section('style')

@endsection

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

        <div class="form-group mb-4">
            <label for="sort_by">Сортировка по</label>
            <select class="form-control" id="sort_by">
                <option value="username">Имя пользователя</option>
                <option value="email">Email</option>
                <option value="status">Статус</option>
            </select>
        </div>

        <div class="form-group mb-4">
            <label for="sort_direction">Направление сортировки</label>
            <select class="form-control" id="sort_direction">
                <option value="asc">От A до Я</option>
                <option value="desc">От Я до A</option>
            </select>
        </div>

        <div id="task-list" class="list-group">

        </div>

        <div id="pagination" class="mt-3">

        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            let currentPage = 1;
            let currentSort = 'username';
            let sortDirection = 'asc';
            let accessToken = localStorage.getItem('access_token');
            let loggedIn = {{ auth()->check() ? 'true' : 'false' }};

            // Функция для загрузки задач
            function loadTasks(page = 1) {
                $.ajax({
                    url: `/api/tasks?page=${page}&sortBy=${currentSort}&direction=${sortDirection}`,
                    method: 'GET',
                    success: function (data) {
                        $('#task-list').empty();
                        data.data.forEach(task => {
                            const status = task.status === "1";
                            const isEditedByAdmin = task.is_edited_by_admin === "1";

                            $('#task-list').append(`
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>${task.username}</strong> (${task.email})<br>
                                        ${task.task_text} ${isEditedByAdmin ? '(отредактировано администратором)' : ''}
                                        <br>
                                        <span class="badge ${status ? 'badge-success' : 'badge-danger'}">
                                            ${status ? 'Выполнено' : 'Не выполнено'}
                                        </span>
                                    </div>
                                    <div>
                                        ${accessToken ? `
                                            <button class="edit-task btn btn-warning btn-sm" data-id="${task.id}" data-text="${task.task_text}" data-toggle="modal" data-target="#editTaskModal">Редактировать</button>
                                            <button class="complete-task btn btn-info btn-sm" data-id="${task.id}" data-toggle="modal" data-target="#completeTaskModal">Завершить</button>
                                        ` : ''}
                                    </div>
                                </div>
                            `);
                        });
                        updatePagination(data);
                    },

                    error: function () {
                        alert('Ошибка при загрузке задач.');
                    }
                });
            }

            // Функция для обновления пагинации
            function updatePagination(data) {
                const pagination = $('#pagination');
                pagination.empty();

                const prevDisabled = data.current_page === 1 ? 'disabled' : '';
                const nextDisabled = data.current_page === data.last_page ? 'disabled' : '';

                // Previous button
                pagination.append(`
                    <button id="prev-page" class="btn btn-secondary" ${prevDisabled}>Назад</button>
                `);

                // Page numbers
                for (let i = 1; i <= data.last_page; i++) {
                    pagination.append(`
                        <button class="page-number btn btn-outline-primary mx-1 ${i === data.current_page ? 'active' : ''}" data-page="${i}">${i}</button>
                    `);
                }

                // Next button
                pagination.append(`
                    <button id="next-page" class="btn btn-secondary" ${nextDisabled}>Вперед</button>
                `);

                // Event binding
                $('#prev-page').off('click').on('click', function () {
                    if (data.current_page > 1) {
                        loadTasks(data.current_page - 1);
                    }
                });

                $('#next-page').off('click').on('click', function () {
                    if (data.current_page < data.last_page) {
                        loadTasks(data.current_page + 1);
                    }
                });

                $('.page-number').off('click').on('click', function () {
                    const selectedPage = $(this).data('page');
                    loadTasks(selectedPage);
                });
            }

            // Загрузка задач при первой загрузке страницы
            loadTasks(currentPage);

            $('#sort_by').change(function() {
                currentSort = $(this).val();
                loadTasks(currentPage);
            });

            $('#sort_direction').change(function() {
                sortDirection = $(this).val();
                loadTasks(currentPage);
            });

            // Добавление задачи
            $('#task-form').on('submit', function (event) {
                event.preventDefault();
                const username = $('#username').val();
                const email = $('#email').val();
                const taskText = $('#task_text').val();

                $.ajax({
                    url: '/api/tasks',
                    method: 'POST',
                    data: {username, email, task_text: taskText},
                    success: function () {
                        loadTasks(currentPage);
                        $('#username').val('');
                        $('#email').val('');
                        $('#task_text').val('');
                    },
                    error: function () {
                        alert('Ошибка при добавлении задачи.');
                    }
                });
            });

            // Редактирование задачи
            $(document).on('click', '.edit-task', function () {
                const taskId = $(this).data('id');
                const taskText = $(this).data('text');
                $('#edit_task_text').val(taskText);

                // Сохранение изменений
                $('#saveEditTask').off('click').on('click', function () {
                    const newTaskText = $('#edit_task_text').val();
                    if (newTaskText) {
                        $.ajax({
                            url: `/api/tasks/${taskId}/update`,
                            method: 'PUT',
                            data: {
                                task_text: newTaskText
                            },
                            headers: {
                                'Authorization': `Bearer ${accessToken}`
                            },
                            success: function(task) {
                                loadTasks(currentPage);
                                $('#editTaskModal').modal('hide');
                                alert('Задача успешно отредактирована!');
                            },
                            error: function() {
                                alert('Ошибка при редактировании задачи.');
                            }
                        });
                    }
                });
            });

            // Завершение задачи
            $(document).on('click', '.complete-task', function () {
                const taskId = $(this).data('id');
                $.ajax({
                    url: `/api/tasks/${taskId}/complete`,
                    method: 'PATCH',
                    headers: {
                        'Authorization': `Bearer ${accessToken}`
                    },
                    success: function () {
                        loadTasks(currentPage);
                        alert('Задача успешно завершена!');
                    },
                    error: function() {
                        alert('Ошибка при завершении задачи.');
                    }
                });
            });
        });
    </script>
@endsection
