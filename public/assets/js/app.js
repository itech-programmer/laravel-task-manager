$(document).ready(function () {
    let currentPage = 1;
    let currentSort = 'username';
    let sortDirection = 'asc';
    let accessToken = localStorage.getItem('access_token'); // Получаем токен из localStorage

    // Функция для загрузки задач
    function loadTasks(page = 1, sortBy = 'username', direction = 'asc') {
        $.ajax({
            url: `/api/tasks?page=${page}&sortBy=${sortBy}&direction=${direction}`,
            method: 'GET',
            success: function (data) {
                $('#task-list').empty();
                data.data.forEach(task => {
                    $('#task-list').append(`
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${task.username}</strong> (${task.email})<br>
                                ${task.task_text} ${task.is_edited_by_admin ? '(отредактировано администратором)' : ''}
                                <br>
                                <span class="badge ${task.completed ? 'badge-success' : 'badge-danger'}">
                                    ${task.completed ? 'Выполнено' : 'Не выполнено'}
                                </span>
                            </div>
                            <div>
                                <button class="edit-task btn btn-warning btn-sm" data-id="${task.id}" data-text="${task.task_text}">Редактировать</button>
                                <button class="complete-task btn btn-info btn-sm" data-id="${task.id}">Завершить</button>
                            </div>
                        </div>
                    `);
                });
                updatePagination(data);
            },
            error: function() {
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
        $('#prev-page').off('click').on('click', function() {
            if (data.current_page > 1) {
                loadTasks(data.current_page - 1, currentSort, sortDirection);
            }
        });

        $('#next-page').off('click').on('click', function() {
            if (data.current_page < data.last_page) {
                loadTasks(data.current_page + 1, currentSort, sortDirection);
            }
        });

        $('.page-number').off('click').on('click', function() {
            const selectedPage = $(this).data('page');
            loadTasks(selectedPage, currentSort, sortDirection);
        });
    }

    // Загрузка задач при первой загрузке страницы
    loadTasks(currentPage, currentSort, sortDirection);

    // Функция для отображения/скрытия кнопки Logout
    function toggleLogoutButton() {
        if (accessToken) {
            $('#logout-button').show();
        } else {
            $('#logout-button').hide();
        }
    }

    // Начальное состояние кнопки Logout
    toggleLogoutButton();

    // Добавление задачи
    $('#task-form').on('submit', function (event) {
        event.preventDefault();
        const username = $('#username').val();
        const email = $('#email').val();
        const taskText = $('#task_text').val();

        $.ajax({
            url: '/api/tasks',
            method: 'POST',
            data: { username, email, task_text: taskText },
            success: function () {
                loadTasks(currentPage, currentSort, sortDirection);
                $('#task-form')[0].reset();
            },
            error: function() {
                alert('Ошибка при добавлении задачи.');
            }
        });
    });

    // Редактирование задачи
    $(document).on('click', '.edit-task', function () {
        if (!accessToken) {
            showLoginModal();
            return;
        }

        const taskId = $(this).data('id');
        const taskText = $(this).data('text');
        const newTaskText = prompt('Введите новый текст задачи', taskText);
        if (newTaskText) {
            $.ajax({
                url: `/api/tasks/${taskId}/update`,
                method: 'PUT',
                data: { task_text: newTaskText },
                headers: {
                    'Authorization': `Bearer ${accessToken}`
                },
                success: function () {
                    loadTasks(currentPage, currentSort, sortDirection);
                },
                error: function() {
                    alert('Ошибка при редактировании задачи.');
                }
            });
        }
    });

    // Завершение задачи
    $(document).on('click', '.complete-task', function () {
        if (!accessToken) {
            showLoginModal();
            return;
        }

        const taskId = $(this).data('id');
        $.ajax({
            url: `/api/tasks/${taskId}/complete`,
            method: 'PATCH',
            headers: {
                'Authorization': `Bearer ${accessToken}`
            },
            success: function () {
                loadTasks(currentPage, currentSort, sortDirection);
            },
            error: function() {
                alert('Ошибка при завершении задачи.');
            }
        });
    });

    // Функция для показа модального окна авторизации
    function showLoginModal() {
        $('#login-modal').modal('show');
    }

    // Дополнительные функции для авторизации, если необходимо
    $('#login-form').on('submit', function(event) {
        event.preventDefault();
        const username = $('#login-username').val();
        const password = $('#login-password').val();

        $.ajax({
            url: '/api/login',
            method: 'POST',
            data: { username, password },
            success: function(response) {
                accessToken = response.access_token;
                localStorage.setItem('access_token', accessToken);
                $('#login-modal').modal('hide');
                loadTasks(currentPage, currentSort, sortDirection);
                toggleLogoutButton();
            },
            error: function() {
                alert('Ошибка при авторизации. Пожалуйста, проверьте данные.');
            }
        });
    });

    // Обработчик для кнопки Logout
    $('#logout-button').on('click', function () {
        $.ajax({
            url: '/api/logout',
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${accessToken}`
            },
            success: function () {
                localStorage.removeItem('access_token');
                accessToken = null;
                alert('Вы вышли из системы.');
                toggleLogoutButton();
            },
            error: function() {
                alert('Ошибка при выходе из системы.');
            }
        });
    });
});
