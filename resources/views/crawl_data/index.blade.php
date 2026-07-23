@extends('layouts.cms')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-end mb-4">

            <div>
                <h2 class="mb-1">Thu thập dữ liệu tuyển sinh</h2>
                <p class="text-muted mb-0">
                    Thu thập dữ liệu từ Vietnamnet và chuẩn bị cho quá trình import dữ liệu.
                </p>
            </div>

            <div class="d-flex align-items-end gap-3">
                <div>
                    <label class="form-label mb-1">Từ năm</label>
                    <input
                        type="text"
                        class="form-control yearpicker"
                        name="from_year"
                        value="{{ $fromYear }}">
                </div>

                <div>
                    <label class="form-label mb-1">Đến năm</label>
                    <input
                        type="text"
                        class="form-control yearpicker"
                        name="to_year"
                        value="{{ $toYear }}">
                </div>

                <button id="crawlBtn" class="btn btn-primary" onclick="startCrawl()">
                    <i class="fa fa-cloud-download"></i>
                    Bắt đầu Crawl
                </button>
            </div>
        </div>

        <div class="row">

            <div class="col-lg-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Trạng thái</h5>

                        <span id="statusBadge" class="badge bg-secondary">
                            Chưa thực hiện
                        </span>

                        <hr>

                        <div class="mb-3">
                            <strong>Nguồn dữ liệu</strong>
                            <div class="text-muted">Vietnamnet</div>
                        </div>

                        <div class="mb-3">
                            <strong>Năm thu thập</strong>
                            <div class="text-muted">2025</div>
                        </div>

                        <div>
                            <strong>Đầu ra</strong>
                            <div class="text-muted">
                                storage/app/diem_chuan.json
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Lần crawl gần nhất</h5>

                        <table class="table table-borderless mb-0">
                            <tr>
                                <th width="220">Thời gian</th>
                                <td>Chưa có</td>
                            </tr>

                            <tr>
                                <th>Số trường</th>
                                <td>-</td>
                            </tr>

                            <tr>
                                <th>Tổng bản ghi</th>
                                <td>-</td>
                            </tr>

                            <tr>
                                <th>Trạng thái</th>
                                <td>
                                <span class="badge bg-secondary">
                                    Chưa thực hiện
                                </span>
                                </td>
                            </tr>
                        </table>

                    </div>
                </div>
            </div>

        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Log thực thi</h5>
            </div>

            <div class="card-body">
            <pre id="crawlLog"
                 class="mb-0"
                 style="height:350px;overflow:auto;background:#1e1e1e;color:#00ff7f;padding:15px;border-radius:8px;">Chưa bắt đầu...</pre>
            </div>
        </div>

    </div>

    <script>
        let polling = null;

        function startCrawl() {

            $('#crawlBtn').prop('disabled', true);

            $('#crawlLog').text('Đang tạo job...\n');

            $.post("{{ route('crawl-data.crawl') }}", {
                _token: "{{ csrf_token() }}",
                from_year: $('[name=from_year]').val(),
                to_year: $('[name=to_year]').val()
            }, function (res) {

                if (!res.success) {
                    $('#crawlBtn').prop('disabled', false);
                    return;
                }

                $('#crawlLog').text('Đã tạo Job: ' + res.job_id + '\n');

                updateBadge('pending');

                if (polling) {
                    clearInterval(polling);
                }

                polling = setInterval(function () {
                    loadStatus(res.job_id);
                }, 2000);

            }).fail(function (xhr) {

                $('#crawlBtn').prop('disabled', false);

                $('#crawlLog').text(xhr.responseText);

                updateBadge('failed');
            });
        }

        function loadStatus(jobId) {

            $.get('/crawl-data/status/' + jobId, function (res) {

                let job = res.data;

                updateBadge(job.status);

                $('#crawlLog').text(job.log ?? '');

                if (job.status === 'completed') {

                    clearInterval(polling);

                    $('#crawlBtn').prop('disabled', false);

                    $('#crawlLog').append(
                        '\n\nHoàn thành.\n' +
                        'Tổng bản ghi: ' + job.total_records
                    );
                }

                if (job.status === 'failed') {

                    clearInterval(polling);

                    $('#crawlBtn').prop('disabled', false);

                    $('#crawlLog').append('\n\nThất bại.');
                }

            }).fail(function () {

                clearInterval(polling);

                $('#crawlBtn').prop('disabled', false);

                updateBadge('failed');

                $('#crawlLog').append('\n\nKhông thể lấy trạng thái job.');
            });
        }

        function updateBadge(status) {

            let badge = $('#statusBadge');

            badge.removeClass('bg-secondary bg-warning bg-primary bg-success bg-danger');

            switch (status) {

                case 'pending':
                    badge.addClass('badge bg-warning');
                    badge.text('Đang chờ');
                    break;

                case 'in_progress':
                    badge.addClass('badge bg-primary');
                    badge.text('Đang crawl');
                    break;

                case 'completed':
                    badge.addClass('badge bg-success');
                    badge.text('Hoàn thành');
                    break;

                case 'failed':
                    badge.addClass('badge bg-danger');
                    badge.text('Thất bại');
                    break;

                default:
                    badge.addClass('badge bg-secondary');
                    badge.text('Chưa thực hiện');
                    break;
            }
        }
    </script>
@endsection
