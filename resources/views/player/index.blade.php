@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Người chơi</h2>
                        <div class="d-flex align-items-center gap-2">
                            {{-- Nút mở bộ lọc --}}
                            <button class="btn btn-sm text-dark btn-outline-secondary d-flex align-items-center"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#filterCollapse"
                                    aria-expanded="false"
                                    aria-controls="filterCollapse">
                                <span class="me-1">🎯</span> Bộ lọc
                            </button>

                            {{-- Nút Export --}}
                            <button type="submit" class="btn btn-success btn-sm export-btn px-4"
                                    onclick="startExport()">
                                <span class="me-2">📥</span>
                                <span class="export-text">Download</span>
                                <div class="spinner-border spinner-border-sm d-none" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div class="collapse mt-3" id="filterCollapse">
                        <form id="filter-players" class="p-3 rounded shadow-sm bg-light">
                            {{-- Hàng 1 --}}
                            <div class="flex-wrap gap-2 mb-3 w-100">
                                {{-- Người chơi mới/cũ --}}
                                <select name="is_new_user" id="is_new_user" class="form-control flex-grow-1 mb-3"
                                        style="min-width:180px;">
                                    <option value="">Tất cả người chơi</option>
                                    <option value="1" {{ request('is_new_user') === '1' ? 'selected' : '' }}>
                                        Người chơi mới
                                    </option>
                                    <option value="0" {{ request('is_new_user') === '0' ? 'selected' : '' }}>
                                        Người chơi cũ
                                    </option>
                                </select>

                                {{-- Phone --}}
                                <input type="text" name="phone" id="phone" inputmode="tel"
                                       pattern="^(\s*\d{10,11}\s*)(,\s*\d{10,11}\s*)*$"
                                       class="form-control flex-grow-1 mb-3"
                                       placeholder="Có thể nhập nhiều số điện thoại: 0901xxxxxx, 0982xxxxxx"
                                       value="{{ old('phones', $phones) }}" style="min-width:180px;">
                            </div>

                            {{-- Hàng 2 --}}
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3 w-100">
                                <div class="border rounded p-3 bg-white shadow-sm flex-grow-1">
                                    <label class="fw-bold mb-2 d-block text-center">Lần đầu đăng nhập</label>
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <input type="text" class="form-control datepicker" name="from_date"
                                               value="{{ $fromDate }}" placeholder="Từ ngày">
                                        <input type="text" class="form-control datepicker" name="to_date"
                                               value="{{ $toDate }}" placeholder="Đến ngày">
                                    </div>
                                </div>

                                {{--                                    <div class="form-group d-flex gap-2 flex-fill mb-0">--}}
                                {{--                                        <input type="text" name="from_date" id="from_date"--}}
                                {{--                                               class="form-control datepicker" placeholder="Từ ngày"--}}
                                {{--                                               value="{{ request('from_date', now()->format('d-m-Y')) }}">--}}
                                {{--                                        <input type="text" name="to_date" id="to_date"--}}
                                {{--                                               class="form-control datepicker" placeholder="Đến ngày"--}}
                                {{--                                               value="{{ request('to_date', now()->format('d-m-Y')) }}">--}}
                                {{--                                    </div>--}}
                            </div>


                            <div class="d-flex justify-content-center gap-3">
                                <button class="btn btn-sm btn-primary px-4" type="submit">
                                    Tìm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Họ</th>
                                <th>Tên</th>
                                <th>Sđt</th>
                                <th>Email</th>
                                <th>Người chơi</th>
                                <th>Bánh kẹo đã ăn</th>
                                <th>Điều khoản</th>
                                <th>Chấp nhập điều khoản lúc</th>
                                <th>Lần đầu đăng nhập</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $key => $player)
                                <tr>
                                    <td>{{ $players->firstItem() + $key }}</td>
                                    <td>{{ $player['last_name'] }}</td>
                                    <td>{{ $player['first_name'] }}</td>
                                    <td>{{ $player['phone'] }}</td>
                                    <td>{{ $player['email'] }}</td>
                                    <td>{{ $player['user_type'] }}</td>
                                    <td>{{ $player['answers'] }}</td>
                                    <td>{{ $player['terms_of_use'] }}</td>
                                    <td>{{ $player['confirmed_terms_at'] }}</td>
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
