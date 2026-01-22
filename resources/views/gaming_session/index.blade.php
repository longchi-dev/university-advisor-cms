@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Lượt chơi</h2>
                        <div class="d-flex align-items-center gap-2">
                            <form id="filter-gaming-sessions" method="GET" class="d-flex gap-2 align-items-center mb-0 w-100">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-group d-flex gap-2 flex-fill mb-0">
                                        <input type="text" name="from_date" id="from_date"
                                               class="form-control datepicker" placeholder="Từ ngày"
                                               value="{{ request('from_date', now()->format('d-m-Y')) }}">
                                        <input type="text" name="to_date" id="to_date"
                                               class="form-control datepicker" placeholder="Đến ngày"
                                               value="{{ request('to_date', now()->format('d-m-Y')) }}">
                                    </div>

                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-primary" type="submit">Tìm</button>
                                    </div>

                                    {{-- Nút Export --}}
{{--                                    <button type="submit" class="btn btn-success btn-sm export-btn px-4" onclick="startExport()">--}}
{{--                                        <span class="me-2">📥</span>--}}
{{--                                        <span class="export-text">Download CSV</span>--}}
{{--                                        <div class="spinner-border spinner-border-sm d-none" role="status">--}}
{{--                                            <span class="visually-hidden">Loading...</span>--}}
{{--                                        </div>--}}
{{--                                    </button>--}}

                                    {{-- Nút Export Excel --}}
{{--                                    <button type="submit" class="btn btn-success btn-sm export-excel-btn px-4"--}}
{{--                                            onclick="startExportExcel()">--}}
{{--                                        <span class="me-2">📥</span>--}}
{{--                                        <span class="export-excel-text">Download Excel</span>--}}
{{--                                        <div class="spinner-excel-border spinner-border-sm d-none" role="status">--}}
{{--                                            <span class="visually-hidden">Loading...</span>--}}
{{--                                        </div>--}}
{{--                                    </button>--}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tên người chơi</th>
                                <th>Địa chỉ IP</th>
                                <th>Điều khoản</th>
                                <th>Ảnh gốc</th>
                                <th>Ảnh đã tạo</th>
                                <th>Ảnh có khung</th>
                                <th>Thời gian bắt đầu</th>
                                <th>Thời gian kết thúc</th>
                                <th>Thời gian chia sẻ</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gamingSessions as $key => $gamingSession)
                                <tr>
                                    <td>{{ $gamingSessions->firstItem() + $key }}</td>
                                    <th>{{ $gamingSessions['player_name'] }}</th>
                                    <td>{{ $gamingSession['ip_address'] }}</td>
                                    <td>{{ $gamingSession['terms_of_use'] }}</td>

                                    {{-- Ảnh upload gốc --}}
                                    <td>
                                        <a target="_blank" href="{{ $gamingSession['upload'] }}">
                                            <img src="{{ $gamingSession['upload'] }}"
                                                 alt="Ảnh upload"
                                                 class="img-thumbnail rounded"
                                                 style="width: 100px; height: auto; object-fit: cover;">
                                        </a>
                                    </td>

                                    {{-- Ảnh đã chọn từ outcome --}}
                                    <td>
                                        <a target="_blank" href="{{ $gamingSession['outcome_chosen'] }}">
                                            <img src="{{ $gamingSession['outcome_chosen'] }}"
                                                 alt="Ảnh đã tạo"
                                                 class="img-thumbnail rounded border-success"
                                                 style="width: 100px; height: auto; object-fit: cover;">
                                        </a>
                                    </td>

                                    {{-- Ảnh có khung --}}
                                    <td>
                                        <a target="_blank" href="{{ $gamingSession['image_has_frame'] }}">
                                            <img src="{{ $gamingSession['image_has_frame'] }}"
                                                 alt="Ảnh đã tạo"
                                                 class="img-thumbnail rounded border-success"
                                                 style="width: 100px; height: auto; object-fit: cover;">
                                        </a>
                                    </td>

                                    {{-- Thời gian bắt đầu --}}
                                    <td>{{ $gamingSession['started_at'] }}</td>
                                    {{-- Thời gian kết thúc --}}
                                    <td>{{ $gamingSession['finished_at'] }}</td>
                                    {{-- Thời gian share fb --}}
                                    <td>{{ $gamingSession['share_facebook_at'] }}</td>

                                    {{-- Hành động --}}
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('gaming-session.show', ['sessionId' => $gamingSession['id']]) }}"
                                               class="btn btn-primary btn-icon d-flex align-items-center justify-content-center me-1"
                                               style="width: 36px; height: 36px;"
                                               title="Xem chi tiết">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <x-pagination :data="$gamingSessions" />
        </div>
    </div>
@endsection
