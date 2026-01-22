
@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h2 style="margin: 0 !important;">Lượt chơi</h2>
                    <form>
                        <div class="form-group">
                            <div class="input-group d-flex align-items-center">
                                <input type="text" class="me-2 form-control datepicker" name="from_date" value="{{$fromDate}}"
                                       placeholder="Ngày bắt đầu">
                                <input type="text" class="me-2 form-control datepicker" name="to_date" value="{{$toDate}}"
                                       placeholder="Ngày kết thúc">
                                <div class="input-group-append ms-2">
                                    <button class="btn btn-sm btn-primary" type="submit">Tìm</button>
{{--                                    <button class="btn btn-sm btn-warning" type="button">Đặt lại</button>--}}
{{--                                    <a href="#" id="export-btn" class="btn btn-sm btn-secondary" >--}}
{{--                                        <div>--}}
{{--                                            <i class="fa fa-download me-2"></i>--}}
{{--                                            <span>Xuất file</span>--}}
{{--                                        </div>--}}
{{--                                        <div id="progress_div" class="d-none">--}}
{{--                                            <progress id="progress-bar" value="0" max="100"></progress>--}}
{{--                                            <p class="message" id="progress-text"></p>--}}
{{--                                        </div>--}}
{{--                                    </a>--}}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>STT</th>
                            <th>Ảnh gốc</th>
                            <th>Ảnh đã chọn</th>
                            <th>Ảnh có khung</th>
                            <th>Ngày</th>
                            <th>Thời gian bắt đầu</th>
                            <th>Thời gian kết thúc</th>
                            <th>Chia sẻ lúc</th>
                            <th>Khung</th>
                            <th>Điều khoản</th>
                            <th>Từ khoá</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($gamingSessions as $key => $gamingSession)
                            <tr>
                                <td>{{ $gamingSessions->firstItem() + $key }}</td>

                                {{-- Ảnh upload gốc --}}
                                <td>
                                    <img src="{{ $gamingSession['upload'] }}"
                                         alt="Ảnh upload"
                                         class="img-thumbnail rounded"
                                         style="width: 100px; height: auto; object-fit: cover;">
                                </td>

                                {{-- Ảnh đã chọn từ outcome --}}
                                <td>
                                    <img src="{{ $gamingSession['choose'] }}"
                                         alt="Ảnh đã chọn"
                                         class="img-thumbnail rounded border-success"
                                         style="width: 100px; height: auto; object-fit: cover;">
                                </td>
                                {{-- Ảnh có khung--}}
                                <td>
                                    <img src="{{ $gamingSession['hasFrame'] }}"
                                         alt="Ảnh có khung"
                                         class="img-thumbnail rounded border-success"
                                         style="width: 100px; height: auto; object-fit: cover;">
                                </td>

                                {{-- Ngày tạo --}}
                                <td>{{ \Carbon\Carbon::parse($gamingSession['date'])->format('d/m/Y') }}</td>

                                {{-- Thời gian bắt đầu --}}
                                <td>
                                    {{ $gamingSession['started_at']
                                        ? \Carbon\Carbon::parse($gamingSession['started_at'])->format('d/m/Y H:i')
                                        : '-' }}
                                </td>

                                {{-- Thời gian kết thúc --}}
                                <td>
                                    {{ $gamingSession['finished_at']
                                        ? \Carbon\Carbon::parse($gamingSession['finished_at'])->format('d/m/Y H:i')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $gamingSession['share_facebook_at']
                                        ? \Carbon\Carbon::parse($gamingSession['share_facebook_at'])->format('d/m/Y H:i')
                                        : '-' }}
                                </td>

                                <td>
                                    {{ $gamingSession['frame'] }}
                                </td>

                                <td>
                                    {{ $gamingSession['terms_of_use'] }}
                                </td>

                                {{-- Keywords --}}
                                <td>
                                        <div style="max-width: 200px; white-space: normal;">
                                            {!! $gamingSession['keyword_label'] !!}
                                        </div>
                                </td>

                                {{-- Hành động --}}
                                <td>
                                    <a href="{{ route('sessions.show', ['sessionId' => $gamingSession['id']]) }}">
                                        <button  type="button" class="btn btn-primary btn-rounded btn-icon">
                                            <i class="mdi mdi-eye"></i>
                                        </button>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                    <x-pagination :data="$gamingSessions" />
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function () {
            $("#export-btn").on('click', (function () {
                const params = new URLSearchParams(window.location.search);

                const data = {
                    from_date: params.get('from_date'),
                    to_date: params.get('to_date'),
                    has_reward: params.get('has_reward'),
                };
                $.post(admin_url + '/export/gamingSession', data, function (response) {
                    if (response.message !== 'OK') {
                        $('.modal-title').text(response.message).css('color', 'red');
                    } else {
                        const logId = response.data.export_log_id;

                        document.querySelector('#progress_div').classList.remove('d-none');
                        $('#progress-bar').val(0).attr('max', 100);
                        $('#progress-text').text("Đang xử lý...");

                        checkExportStatus(logId);
                    }
                });
            }));

            function checkExportStatus(logId) {
                let interval = setInterval(function () {
                    $.get(admin_url + '/export/status/' + logId, function (response) {
                        if (response.data.status === 'FAILED') {
                            clearInterval(interval);
                            $('#progress-text').text("Xuất file thất bại!");
                            $('#progress-bar').val(0);
                            document.querySelector('#progress_div').classList.add('d-none');
                            return;
                        }

                        if (response.data.status === 'COMPLETED') {
                            clearInterval(interval);
                            $('#progress-bar').val(100);
                            $('#progress-text').text(response.data.processing_records + "/" + response.data.total_records + " dòng đã hoàn tất");
                            document.querySelector('#progress_div').classList.add('d-none');
                            window.open(response.data.path, '_blank');
                            return;
                        }

                        // Đang xử lý
                        const percent = response.data.total_records > 0 ?
                            Math.floor((response.data.processing_records /
                                response.data.total_records) * 100) : 0;

                        $('#progress-bar').val(percent);
                        $('#progress-text').text(response.data.processing_records + "/" + response.data.total_records + " dòng...");
                    });
                }, 2000);
            }
        });
    </script>
@endpush


