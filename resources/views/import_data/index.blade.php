@extends('layouts.cms')

@section('content')
    <div class="container-fluid">

        <div class="mb-4">
            <h2>Import dữ liệu tuyển sinh</h2>
            <p class="text-muted">
                Import dữ liệu điểm chuẩn từ file JSON vào hệ thống.
            </p>
        </div>

        <div class="row">

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <h5 class="fw-bold mb-3">
                            Upload dữ liệu
                        </h5>

                        <input
                            type="file"
                            id="jsonFile"
                            class="form-control"
                            accept=".json"
                        >

                        <button
                            id="importBtn"
                            onclick="startImport()"
                            class="btn btn-primary mt-3 w-100">

                            <i class="fa fa-upload"></i>
                            Import
                        </button>

                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="fw-bold mb-3">
                            Trạng thái import
                        </h5>

                        <span id="statusBadge" class="badge bg-secondary">
                        Chưa thực hiện
                    </span>

                        <hr>

                        <pre id="importLog"
                             class="mb-0"
                             style="height:300px;overflow:auto;background:#1e1e1e;color:#00ff7f;padding:15px;border-radius:8px;">
Chưa bắt đầu...
                    </pre>

                    </div>

                </div>
            </div>

        </div>

    </div>

    <script>
        let polling = null;

        function startImport() {

            let file = $('#jsonFile')[0].files[0];

            if (!file) {
                alert('Vui lòng chọn file JSON');
                return;
            }

            let formData = new FormData();

            formData.append('file', file);
            formData.append('_token', '{{ csrf_token() }}');

            $('#importBtn').prop('disabled', true);

            $('#importLog').text('Đang tạo import job...');

            $.ajax({
                url: "{{ route('import-data.import') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function(res) {

                    if (!res.success) {
                        return;
                    }

                    $('#importLog').text(
                        'Job: ' + res.job_id
                    );

                    updateBadge('pending');

                    if (polling) {
                        clearInterval(polling);
                    }

                    polling = setInterval(function() {
                        loadStatus(res.job_id);
                    }, 2000);
                },

                error: function(xhr) {

                    $('#importBtn').prop('disabled', false);

                    $('#importLog').text(
                        xhr.responseText
                    );
                }
            });
        }


        function loadStatus(jobId) {

            $.get('/import-data/status/' + jobId, function(res) {

                let job = res.data;

                updateBadge(job.status);

                $('#importLog').text(
                    `Processed: ${job.processed}/${job.total}\n`
                );

                if (job.log) {
                    $('#importLog').append(job.log);
                }

                if (job.status === 'completed' || job.status === 'failed') {

                    clearInterval(polling);

                    $('#importBtn').prop('disabled', false);
                }

            });
        }


        function updateBadge(status) {

            let badge = $('#statusBadge');

            badge.removeClass(
                'bg-secondary bg-warning bg-primary bg-success bg-danger'
            );

            switch(status) {

                case 'pending':
                    badge
                        .addClass('badge bg-warning')
                        .text('Đang chờ');
                    break;

                case 'in_progress':
                    badge
                        .addClass('badge bg-primary')
                        .text('Đang import');
                    break;

                case 'completed':
                    badge
                        .addClass('badge bg-success')
                        .text('Hoàn thành');
                    break;

                case 'failed':
                    badge
                        .addClass('badge bg-danger')
                        .text('Thất bại');
                    break;

                default:
                    badge
                        .addClass('badge bg-secondary')
                        .text('Chưa thực hiện');
            }
        }
    </script>
@endsection
