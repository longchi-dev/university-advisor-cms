@extends('layouts.cms')
@section('content')
    @php
        $breadcrumbItems = [
            [
                'label' => 'Người dùng',
                'href' => route('users.index'), // Thay bằng tên route danh sách user của bạn nếu khác
            ],
            [
                'label' => 'Chi tiết hồ sơ',
            ],
        ];
    @endphp

    <div class="card">
        <div class="card-body">
            {{-- Sử dụng component Breadcrumb tự tạo của bạn --}}
            <x-nav-breadcrumb :items="$breadcrumbItems"/>

            <div class="position-relative">
                <div class="mb-3 mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Hồ sơ người dùng</h2>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                {{-- Cột trái: Thông tin tài khoản chính --}}
                <div class="col-md-4">
                    <div class="card shadow-sm border mb-4">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                {{-- Tạo avatar giả lập bằng ký tự đầu của tên --}}
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center shadow"
                                     style="width: 80px; height: 80px; font-size: 2rem; font-weight: bold;">
                                    {{ mb_substr($user['name'] ?? '', 0, 1) }}
                                </div>
                            </div>
                            <h4 class="card-title mb-1 fw-bold">{{ $user['name'] }}</h4>
                            <p class="text-muted small mb-3">{{ $user['email'] }}</p>
                            <span class="badge bg-success px-3 py-2 rounded-pill">
                                {{ $user['profile']['mbti_type'] ?? 'Chưa xác định MBTI' }}
                            </span>
                        </div>
                        <hr class="my-0">
                        <div class="card-body">
                            <h6 class="fw-bold mb-3">Thông tin hệ thống</h6>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">IP Đăng nhập cuối:</span>
                                <span class="fw-medium">{{ $user['last_login_ip'] ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 small">
                                <span class="text-muted">Đăng nhập cuối:</span>
                                <span class="fw-medium">{{ $user['last_login_at'] ?? 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-muted">Ngày tạo tài khoản:</span>
                                <span class="fw-medium">{{ $user['created_at'] ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Cột phải: Chi tiết User Profile --}}
                <div class="col-md-8">
                    <div class="card shadow-sm border">
                        <div class="card-header bg-white py-3">
                            <h5 class="card-title m-0 text-primary fw-bold">🎯 Hồ Sơ Năng Lực Học Tập</h5>
                        </div>
                        <div class="card-body">
                            @if(!empty($user['profile']))
                                <div class="row g-4">
                                    {{-- Điểm số --}}
                                    <div class="col-sm-6">
                                        <label class="text-muted small d-block mb-1">Điểm số hiện tại</label>
                                        <div class="d-flex align-items-center">
                                            <span class="fs-3 fw-bold text-success me-2">{{ $user['profile']['score'] ?? 0 }}</span>
                                            <span class="text-muted small">điểm</span>
                                        </div>
                                    </div>

                                    {{-- Phong cách làm việc --}}
                                    <div class="col-sm-6">
                                        <label class="text-muted small d-block mb-1">Phong cách làm việc</label>
                                        <span class="badge bg-info text-dark p-2 fs-6 fw-normal">
                                            {{ $user['profile']['work_style'] ?? 'Chưa cập nhật' }}
                                        </span>
                                    </div>

                                    {{-- Ngành học mục tiêu --}}
                                    <div class="col-sm-6">
                                        <label class="text-muted small d-block mb-1">Ngành học mục tiêu</label>
                                        <div class="p-2 border rounded bg-light fw-medium text-dark">
                                            {{ $user['profile']['target_major'] ?? 'Chưa đăng ký' }}
                                        </div>
                                    </div>

                                    {{-- Mục tiêu nghề nghiệp --}}
                                    <div class="col-sm-6">
                                        <label class="text-muted small d-block mb-1">Mục tiêu nghề nghiệp</label>
                                        <div class="p-2 border rounded bg-light fw-medium text-dark">
                                            {{ $user['profile']['career_goal'] ?? 'Chưa xác định' }}
                                        </div>
                                    </div>

                                    {{-- Môn học yêu thích --}}
                                    <div class="col-12">
                                        <label class="text-muted small d-block mb-2">⭐ Môn học yêu thích</label>
                                        <div>
                                            @if(!empty($user['profile']['favorite_subjects']) && is_array($user['profile']['favorite_subjects']))
                                                @foreach($user['profile']['favorite_subjects'] as $subject)
                                                    <span class="badge bg-primary me-1 mb-1 p-2">{{ $subject }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted small italic">Chưa chọn môn học yêu thích</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Môn học còn yếu --}}
                                    <div class="col-12">
                                        <label class="text-muted small d-block mb-2">⚠️ Môn học còn yếu</label>
                                        <div>
                                            @if(!empty($user['profile']['weak_subjects']) && is_array($user['profile']['weak_subjects']))
                                                @foreach($user['profile']['weak_subjects'] as $subject)
                                                    <span class="badge bg-danger me-1 mb-1 p-2">{{ $subject }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted small italic">Chưa chọn môn học yếu</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @else
                                {{-- Trường hợp mảng profile là null --}}
                                <div class="text-center py-5 text-muted">
                                    <span class="fs-1 d-block mb-2">📭</span>
                                    <p>Người dùng này hiện tại chưa cập nhật thông tin Profile cá nhân.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
