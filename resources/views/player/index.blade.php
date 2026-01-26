@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Người chơi</h2>
                        <div class="d-flex align-items-center gap-2">
                            <form id="filter-players" method="GET" class="d-flex gap-2 align-items-center mb-0 w-100">
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
{{--                                <button type="submit" class="btn btn-success btn-sm export-btn px-4"--}}
{{--                                        onclick="startExport()">--}}
{{--                                    <span class="me-2">📥</span>--}}
{{--                                    <span class="export-text">Download CSV</span>--}}
{{--                                    <div class="spinner-border spinner-border-sm d-none" role="status">--}}
{{--                                        <span class="visually-hidden">Loading...</span>--}}
{{--                                    </div>--}}
{{--                                </button>--}}

                                {{-- Nút Export Excel --}}
{{--                                <button type="submit" class="btn btn-success btn-sm export-excel-btn px-4"--}}
{{--                                        onclick="startExportExcel()">--}}
{{--                                    <span class="me-2">📥</span>--}}
{{--                                    <span class="export-excel-text">Download Excel</span>--}}
{{--                                    <div class="spinner-excel-border spinner-border-sm d-none" role="status">--}}
{{--                                        <span class="visually-hidden">Loading...</span>--}}
{{--                                    </div>--}}
{{--                                </button>--}}
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
                                <th>URL</th>
                                <th>Lần đầu đăng nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $key => $player)
                                <tr>
                                    <td>{{ $players->firstItem() + $key }}</td>
                                    <td>{{ $player['player_name'] }}</td>
                                    <td>
                                        @if (!empty($player['full_url']))
                                            @php
                                                $id = 'urlCollapse' . $loop->index;
                                                $short = \Illuminate\Support\Str::limit($player['full_url'], 30);
                                            @endphp

                                            <span
                                                    data-bs-toggle="collapse"
                                                    href="#{{ $id }}"
                                                    role="button"
                                                    aria-expanded="false"
                                                    aria-controls="{{ $id }}"
                                                    class="text-body text-decoration-none cursor-pointer fw-medium"
                                                    style="cursor: pointer;"
                                            >
                                                {{ $short }}
                                            </span>

                                            <div class="collapse mt-1" id="{{ $id }}">
                                                <small class="text-muted">{{ $player['full_url'] }}</small>
                                            </div>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $player['created_at'] }}</td>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <x-pagination :data="$players"/>
        </div>
    </div>
@endsection
