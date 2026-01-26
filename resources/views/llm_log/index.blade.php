@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">LLM Logs</h2>
                        <div class="d-flex align-items-center gap-2">
                            <form id="filter-llm-logs" method="GET" class="d-flex gap-2 align-items-center mb-0 w-100">
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
                            <th>LLM Name</th>
                            <th>LLM Model</th>
                            <th>LLM Key</th>
                            <th>Prompt</th>
                            <th>Image</th>
                            <th>Response</th>
                            <th>Request At</th>
                            <th>Response At</th>
                            <th>Exec Time (s)</th>
                            <th>Ngày tạo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($llmLogs as $key => $llmLog)
                            <tr>
                                <td>{{ $llmLogs->firstItem() + $key }}</td>

                                {{-- LLM Name --}}
                                <td>
                                    <span class="badge badge-info">{{ $llmLog->llm_name ?? '-' }}</span>
                                </td>

                                {{-- LLM Model --}}
                                <td>
                                    <span class="badge badge-secondary">{{ $llmLog->llm_model ?? '-' }}</span>
                                </td>

                                {{-- LLM Key --}}
                                <td>
                                    @if(Str::startsWith($llmLog->llm_key, 'sk-'))
                                        {{-- Lấy 50 ký tự đầu --}}
                                        {{ substr($llmLog->llm_key, 0, 50) . (strlen($llmLog->llm_key) > 50 ? '...' : '') }}
                                    @else
                                        {{-- Bỏ 10 ký tự đầu, lấy phần còn lại --}}
                                        {{ '...' . substr($llmLog->llm_key, 5) }}
                                    @endif
                                </td>

                                {{-- Prompt --}}
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-link p-0 show_modal"
                                            data-bs-toggle="modal"
                                            data-bs-target="#promptModal{{ $llmLog->id }}">
                                        Xem
                                    </button>

                                    {{-- Modal hiển thị full Prompt --}}
                                    <div class="modal fade" id="promptModal{{ $llmLog->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Prompt (ID: {{ $llmLog->id }})</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body" style="white-space: pre-wrap;">
                                                    {{ $llmLog->prompt }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Image URL --}}
                                <td>
                                    @if($llmLog->image_url)
                                        <a href="{{ $llmLog->image_url }}" target="_blank">
                                            <img src="{{ $llmLog->image_url }}"
                                                 alt="LLM Image"
                                                 class="img-thumbnail rounded"
                                                 style="width: 80px; height: auto; object-fit: cover;"
                                                 onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <span style="display: none; color: #666;">No Image</span>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Response --}}
                                <td>
                                    <div style="max-width: 300px; white-space: normal; overflow: hidden; text-overflow: ellipsis;">
                                        {{ Str::limit($llmLog->response, 150) ?? '-' }}
                                    </div>
                                    @if(strlen($llmLog->response) > 150)
                                        <button type="button"
                                                class="btn btn-sm btn-link p-0 show_modal"
                                                data-bs-toggle="modal"
                                                data-bs-target="#responseModal{{ $llmLog->id }}">
                                            Xem
                                        </button>
                                    @endif

                                    {{-- Modal hiển thị full Response --}}
                                    <div class="modal fade" id="responseModal{{ $llmLog->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Response (ID: {{ $llmLog->id }})</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body" style="white-space: pre-wrap;">
                                                    {{ $llmLog->response }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Request At --}}
                                <td>
                                    {{ $llmLog->request_at
                                        ? \Carbon\Carbon::parse($llmLog->request_at)->format('d/m/Y H:i:s')
                                        : '-' }}
                                </td>

                                {{-- Response At --}}
                                <td>
                                    {{ $llmLog->response_at
                                        ? \Carbon\Carbon::parse($llmLog->response_at)->format('d/m/Y H:i:s')
                                        : '-' }}
                                </td>

                                {{-- Exec Time --}}
                                <td>
                                    @if($llmLog->exec_time)
                                        <span class="badge badge-{{ $llmLog->exec_time > 5 ? 'danger' : ($llmLog->exec_time > 2 ? 'warning' : 'success') }}">
                                            {{ number_format($llmLog->exec_time, 2) }}s
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Ngày tạo --}}
                                <td>{{ \Carbon\Carbon::parse($llmLog->created_at)->format('d-m-Y H:i:s') }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <x-pagination :data="$llmLogs" />
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('show.bs.modal', function (event) {
            const modal = event.target;
            document.body.appendChild(modal);
        });
    </script>
@endpush
