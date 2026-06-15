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
                                               value="{{ request('from_date', $fromDate) }}">
                                        <input type="text" name="to_date" id="to_date"
                                               class="form-control datepicker" placeholder="Đến ngày"
                                               value="{{ request('to_date', $toDate) }}">
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
                    <table class="table table-hover table-striped">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Người dùng</th>
                            <th>Model</th>
                            <th>Loại Prompt</th>
                            <th>Prompt</th>
                            <th>Response</th>
                            <th>Tokens (I/O/T)</th>
                            <th>Thời gian chạy</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($llmLogs as $key => $llmLog)
                            @php
                                // Tạo một ID độc nhất cho Modal dựa vào index vòng lặp
                                $modalId = $llmLogs->firstItem() + $key;
                            @endphp
                            <tr>
                                <td>{{ $modalId }}</td>

                                {{-- Người dùng --}}
                                <td>
                                    <strong>{{ $llmLog['name'] ?? 'N/A' }}</strong><br>
                                    <small class="text-muted">{{ $llmLog['email'] ?? '-' }}</small>
                                </td>

                                {{-- Model --}}
                                <td>
                                    <span class="badge bg-dark">{{ $llmLog['model'] ?? '-' }}</span>
                                </td>

                                {{-- Loại Prompt --}}
                                <td>
                                    <span class="badge bg-info text-light">{{ $llmLog['prompt_type'] ?? '-' }}</span>
                                </td>

                                {{-- Prompt --}}
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary show_modal"
                                            data-bs-toggle="modal"
                                            data-bs-target="#promptModal{{ $modalId }}">
                                        Xem chi tiết
                                    </button>

                                    {{-- Modal hiển thị full Prompt --}}
                                    <div class="modal fade" id="promptModal{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Prompt chi tiết (#{{ $modalId }})</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    {{-- Kiểm tra nếu là mảng JSON (chứa role, content...) thì format đẹp, ngược lại in chuỗi thường --}}
                                                    <pre style="background: #272822; color: #f8f8f2; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: auto; white-space: pre-wrap;">@if(is_array($llmLog['prompt'])){{ json_encode($llmLog['prompt'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}@else{{ $llmLog['prompt'] }}@endif</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Response --}}
                                <td>
                                    <div style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                        @if(is_array($llmLog['response']))
                                            {{ Str::limit(json_encode($llmLog['response'], JSON_UNESCAPED_UNICODE), 100) }}
                                        @else
                                            {{ Str::limit($llmLog['response'], 100) ?? '-' }}
                                        @endif
                                    </div>

                                    <button type="button"
                                            class="btn btn-sm btn-link p-0 show_modal"
                                            data-bs-toggle="modal"
                                            data-bs-target="#responseModal{{ $modalId }}">
                                        Xem đầy đủ
                                    </button>

                                    {{-- Modal hiển thị full Response --}}
                                    <div class="modal fade" id="responseModal{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Response chi tiết (#{{ $modalId }})</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    @if(!empty($llmLog['error_message']))
                                                        <div class="alert alert-danger mb-3">
                                                            <strong>Lỗi:</strong> {{ $llmLog['error_message'] }}
                                                        </div>
                                                    @endif

                                                    {{-- Sử dụng thẻ <pre> kết hợp định dạng JSON đẹp mắt --}}
                                                    <pre style="background: #272822; color: #f8f8f2; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: auto; white-space: pre-wrap;">@if(is_array($llmLog['response'])){{ json_encode($llmLog['response'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}@else{{ $llmLog['response'] }}@endif</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Tokens (Input / Output / Total) --}}
                                <td>
                                    <small>
                                        In: <b>{{ number_format($llmLog['tokens_input'] ?? 0) }}</b><br>
                                        Out: <b>{{ number_format($llmLog['tokens_output'] ?? 0) }}</b><br>
                                        Tổng: <span class="text-primary">{{ number_format($llmLog['tokens_total'] ?? 0) }}</span>
                                    </small>
                                </td>

                                {{-- Exec Time --}}
                                <td>
                                    @if($llmLog['execute_time_ms'])
                                        @php $seconds = $llmLog['execute_time_ms'] / 1000; @endphp
                                        <span class="badge {{ $seconds > 5 ? 'bg-danger' : ($seconds > 2 ? 'bg-warning text-dark' : 'bg-success') }}">
                                            {{ number_format($seconds, 2) }}s
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Trạng thái --}}
                                <td>
                                    @if(($llmLog['status'] ?? '') === 'success')
                                        <span class="badge bg-success">Success</span>
                                    @else
                                        <span class="badge bg-danger" title="{{ $llmLog['error_message'] ?? 'Thất bại' }}">Failed</span>
                                    @endif
                                </td>

                                {{-- Ngày tạo --}}
                                <td>
                                    <small>{{ $llmLog['created_at'] ?? '-' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center text-muted">Không tìm thấy bản ghi log nào trong khoảng thời gian này.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                <x-pagination :data="$llmLogs" />
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        // Fix lỗi hiển thị z-index chồng chéo của Bootstrap Modal trong Admin Dashboard
        document.addEventListener('show.bs.modal', function (event) {
            const modal = event.target;
            document.body.appendChild(modal);
        });
    </script>
@endpush
