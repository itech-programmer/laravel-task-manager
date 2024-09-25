<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<!-- begin::Head -->
<head>
    <!-- begin::Metas -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- end::Metas -->

    <!-- begin::Title -->
    <title>Задачник</title>
    <!-- end::Title -->

    <!-- begin::Vendor -->
    <!-- bootstrap -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- end::Vendor -->

    <!-- begin::App -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets') }}/css/app.css">
    <!-- end::App -->

    <!-- begin::Pages -->
    @yield('style')
    <!-- end::Pages -->

</head>
<!-- end::Head -->
<!-- begin::Body -->
<body>

<!-- begin::Header -->
<header class="bg-light py-3">
    <div class="container d-flex justify-content-between align-items-center">
        <h1 class="h4">Задачник</h1>

            <button id="login-button" class="btn btn-success" data-toggle="modal" data-target="#loginModal">Login</button>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" id="logout-button" class="btn btn-danger">Logout</button>
            </form>

    </div>
</header>
<!-- end::Header -->

@yield('content')

@include('layouts.modal')

<!-- begin::Vendor -->
<!-- jquery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- end::Vendor -->

<!-- begin::App -->
<script src="{{ asset('assets') }}/js/app.js"></script>

<!-- begin::Pages -->
<script>
    $(document).ready(function () {
        let accessToken = localStorage.getItem('access_token');

        function updateAuthButtons() {
            if (accessToken) {
                $('#login-button').hide();
                $('#logout-button').show();
            } else {
                $('#login-button').show();
                $('#logout-button').hide();
            }
        }

        // Начальное состояние кнопок авторизации
        updateAuthButtons();

        $('#login-button').on('click', function () {
            $('#login-modal').modal('show');
        });

        $('#logout-button').on('click', function () {
            localStorage.removeItem('access_token');
            accessToken = null;
            updateAuthButtons();
        });

        $('#login-form').on('submit', function (event) {
            event.preventDefault();
            const username = $('#login-username').val();
            const password = $('#login-password').val();

            $.ajax({
                url: '/api/login',
                method: 'POST',
                data: { username, password },
                success: function (data) {
                    localStorage.setItem('access_token', data.access_token);
                    accessToken = data.access_token;
                    $('#login-modal').modal('hide');
                    updateAuthButtons();
                    location.reload();
                },
                error: function () {
                    alert('Ошибка авторизации. Проверьте email и пароль.');
                }
            });
        });
    });
</script>
@yield('script')
<!-- end::Pages -->

</body>
<!--end::Body-->
</html>
