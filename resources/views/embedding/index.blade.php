@extends('layouts.cms')

@section('content')
    <div class="container-fluid">

        <div class="mb-4">
            <h2>Generate Embedding</h2>
            <p class="text-muted">
                Tạo vector embedding cho dữ liệu tuyển sinh phục vụ tìm kiếm RAG.
            </p>
        </div>

        <div class="row">

            <div class="col-lg-4">
                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="fw-bold mb-3">
                            Thao tác
                        </h5>

                        <p class="text-muted">
                            Hệ thống sẽ tạo embedding cho toàn bộ dữ liệu tuyển sinh đã import.
                        </p>

                        <button
                            id="embeddingBtn"
                            onclick="startEmbedding()"
                            class="btn btn-primary w-100">

                            <i class="fa fa-magic"></i>
                            Generate Embedding

                        </button>

                    </div>

                </div>
            </div>


            <div class="col-lg-8">

                <div class="card shadow-sm border-0">

                    <div class="card-body">

                        <h5 class="fw-bold mb-3">
                            Trạng thái
                        </h5>


                        <span
                            id="statusBadge"
                            class="badge bg-secondary">
                        Chưa thực hiện
                    </span>


                        <hr>


                        <pre id="embeddingLog"
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

        function startEmbedding() {

            $('#embeddingBtn').prop('disabled', true);

            $('#embeddingLog').text(
                'Đang tạo embedding job...'
            );


            $.post("{{ route('embedding.generate') }}", {

                _token: "{{ csrf_token() }}"

            }, function(res) {


                if (!res.success) {

                    $('#embeddingBtn').prop('disabled', false);

                    return;
                }


                $('#embeddingLog').text(
                    'Job: ' + res.job_id
                );


                updateBadge('pending');


                if (polling) {
                    clearInterval(polling);
                }


                polling = setInterval(function(){

                    loadStatus(res.job_id);

                }, 2000);


            }).fail(function(xhr){

                $('#embeddingBtn').prop('disabled', false);

                $('#embeddingLog').text(
                    xhr.responseText
                );

                updateBadge('failed');

            });

        }


        function loadStatus(jobId) {

            $.get(
                '/embedding/status/' + jobId,

                function(res){

                    let job = res.data;


                    updateBadge(job.status);


                    $('#embeddingLog').text(
                        job.log ?? ''
                    );


                    if (
                        job.status === 'completed' ||
                        job.status === 'failed'
                    ){

                        clearInterval(polling);

                        $('#embeddingBtn')
                            .prop('disabled', false);

                    }

                }
            );
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
                        .text('Đang tạo embedding');

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

                    break;

            }

        }
    </script>
@endsection
