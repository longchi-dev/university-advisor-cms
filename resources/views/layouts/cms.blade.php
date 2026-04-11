@php
    $user = \App\Helpers\AuthHelper::getUser();

    $rewardActive = request()->routeIs('reward-management.*');
@endphp
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="admin-url" content="{{ url('/admin') }}">

    <title>{{ config('app.brand_name', 'CMS') }}</title>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/cms/index.js'])

    <!-- plugins:css -->
    <link rel="stylesheet" href="{{asset('assets/vendors/mdi/css/materialdesignicons.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/css/vendor.bundle.base.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendors/font-awesome/css/font-awesome.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.css')}}">

    <!-- Layout styles -->
    <link rel="stylesheet" href="{{asset('assets/css/vertical-light-layout/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

    <!-- Fonts -->
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body class="font-sans antialiased">
<div class="container-scroller">
    <div class="p-0 m-0 row proBanner" id="proBanner">
        <div class="p-0 m-0 col-md-12"></div>
    </div>

    <!-- sidebar -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav mt-3">
            <li class="nav-item nav-profile mx-2">
                <a href="#" class="nav-link" style="cursor:context-menu;">
                    <div class="nav-profile-image">
                        <div class="user-avatar-placeholder">L</div>
                        <span class="login-status online"></span>
                    </div>
                    <div class="nav-profile-text d-flex flex-column ps-3">
                        <span class="mb-1 font-weight-bold">{{ config('app.brand_name', 'CMS') }}</span>
                        <span class="text-muted text-small">Admin Panel</span>
                    </div>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <i class="fa fa-dashboard"></i>
                    <span class="menu-title">Thống kê</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('gaming-session.index') }}">
                    <i class="fa fa-gamepad"></i>
                    <span class="menu-title">Lượt chơi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('player.index') }}">
                    <i class="fa fa-user"></i>
                    <span class="menu-title">Người chơi</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('leader-board.index') }}">
                    <i class="fa fa-trophy"></i>
                    <span class="menu-title">Bảng xếp hạng</span>
                </a>
            </li>
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="{{ route('llm-keys.index') }}">--}}
{{--                    <i class="fa fa-key"></i>--}}
{{--                    <span class="menu-title">Image Key</span>--}}
{{--                </a>--}}
{{--            </li>--}}
            <li class="nav-item">
                <a class="nav-link" href="{{ route('llm_log.index') }}">
                    <i class="fa fa-refresh"></i>
                    <span class="menu-title">LLM Log</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('setting.index') }}">
                    <i class="fa fa-cog"></i>
                    <span class="menu-title">Cài đặt</span>
                </a>
            </li>
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" href="{{ route('prompt-randoms.index') }}">--}}
{{--                    <i class="fa fa-cog"></i>--}}
{{--                    <span class="menu-title">Prompt random</span>--}}
{{--                </a>--}}
{{--            </li>--}}
        </ul>
    </nav>

    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
        <!-- partial:partials/_navbar.html -->
        <nav class="flex-row navbar col-lg-12 col-12 fixed-top d-flex">
            <div class="navbar-menu-wrapper d-flex align-items-stretch justify-content-between pb-3">
                <ul class="navbar-nav navbar-nav-right">

                    <li class="border-0 nav-item nav-profile dropdown">
                        <a class="nav-link dropdown-toggle" id="profileDropdown" href="#" data-bs-toggle="dropdown">
                            <div class="user-avatar-placeholder">A</div>
                            <span class="profile-name ms-2">{{$user?->name ?? 'Admin'}}</span>
                        </a>
                        <div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
                            @if (auth()->user()?->email === config('admin.email_can_change_password'))
                                <a class="dropdown-item" href="{{ route('password.change') }}">
                                    <i class="mdi mdi-lock-reset me-2 text-warning"></i> Đổi mật khẩu
                                </a>
                                <div class="dropdown-divider"></div>
                            @endif

                            <form action="{{ route('logout')}}" method="POST">
                                @csrf
                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); this.closest('form').submit();">
                                    <i class="mdi mdi-logout me-2 text-primary"></i> Đăng xuất </a>
                            </form>
                        </div>
                    </li>
                </ul>

                <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
                    <span class="mdi mdi-menu"></span>
                </button>
            </div>
        </nav>

        <!-- partial main content -->
        <div class="main-panel">
            <div class="content-wrapper px-4 py-4 fade-in">
                @yield('content')
            </div>
            <!-- content-wrapper ends -->
        </div>
    </div>
</div>

<!-- footer -->
<footer class="footer">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <span class="text-muted">Copyright © 2025 {{ config('app.brand_name', 'CMS') }}</span>
        </div>
        <div>
            <span class="text-muted"><i class="mdi mdi-code-tags me-1"></i>Developed by Marvy Co.</span>
        </div>
    </div>
</footer>

<!-- jQuery -->
<script src="{{ asset('assets/vendors/jquery/jquery.min.js') }}"></script>

<!-- plugins:js -->
<script src="{{asset('assets/vendors/flot/jquery.flot.js')}}"></script>
<script src="{{asset('assets/vendors/flot/jquery.flot.resize.js')}}"></script>
<script src="{{asset('assets/vendors/flot/jquery.flot.categories.js')}}"></script>
<script src="{{asset('assets/vendors/flot/jquery.flot.fillbetween.js')}}"></script>
<script src="{{asset('assets/vendors/flot/jquery.flot.stack.js')}}"></script>
<script src="{{asset('assets/vendors/flot/jquery.flot.pie.js')}}"></script>
<script src="{{asset('assets/js/jquery.cookie.js')}}" type="text/javascript"></script>
<script src="{{asset('assets/vendors/chart.js/Chart.min.js')}}"></script>
<script src="{{asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>

<!-- inject:js -->
<script src="{{asset('assets/js/off-canvas.js')}}"></script>
<script src="{{asset('assets/js/hoverable-collapse.js')}}"></script>
<script src="{{asset('assets/js/misc.js')}}"></script>
<script src="{{asset('assets/js/settings.js')}}"></script>
<script src="{{asset('assets/js/todolist.js')}}"></script>

<!-- custom:js -->
<script src="{{asset('assets/js/file-upload.js')}}"></script>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

@if (session('success'))
    <script>
        window.toastSuccess(@json(session('success')));
    </script>
@endif

@if (session('error'))
    <script>
        window.toastError(@json(session('error')));
    </script>
@endif

@stack('js')
</body>
</html>
