@extends('layouts.cms')

@php
    // Hàm hỗ trợ tự động nhận diện và làm đẹp JSON (đặt ngay đầu file)
    if (!function_exists('formatBeautifulJson')) {
        function formatBeautifulJson($data) {
            if (empty($data)) return '-';

            // Nếu là chuỗi, thử decode xem có phải JSON hợp lệ không
            if (is_string($data)) {
                $decoded = json_decode($data, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data = $decoded;
                }
            }

            // Nếu cuối cùng là mảng/object thì format đẹp lại, không thì in nguyên bản
            return is_array($data)
                ? json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
                : $data;
        }
    }
@endphp

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">LLM Logs</h2>
                        <div class="d-flex align-items-center gap-2">
                            <form id="filter-llm-logs" method="GET" class="d-flex gap-2 align-items-center mb-0 w-100">
                                <select name="model" class="form-control w-auto" style="min-width: 150px;">
                                    <option value="">Tất cả model</option>
                                    @foreach($models as $model)
                                        <option value="{{ $model }}" @selected(request('model') == $model)>
                                            {{ $model }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="prompt_type" class="form-control w-auto" style="min-width: 150px;">
                                    <option value="">Tất cả prompt</option>
                                    @foreach($promptTypes as $promptType)
                                        <option value="{{ $promptType }}" @selected(request('prompt_type') == $promptType)>
                                            {{ $promptType }}
                                        </option>
                                    @endforeach
                                </select>

                                <!-- THÊM BỘ LỌC RAG TẠI ĐÂY -->
                                <select name="has_rag" class="form-control w-auto" style="min-width: 160px;">
                                    <option value="">Tất cả (RAG & Non-RAG)</option>
                                    <option value="1" @selected(request('has_rag') === '1')>Có RAG (Has RAG)</option>
                                    <option value="0" @selected(request('has_rag') === '0')>Không RAG (Non-RAG)</option>
                                </select>

                                <div class="d-flex align-items-center gap-2">
                                    <div class="form-group d-flex gap-2 flex-fill mb-0">
                                        <input type="text" name="from_date" id="from_date"
                                               class="form-control datepicker" placeholder="Từ ngày"
                                               value="{{ request('from_date', $fromDate) }}" style="min-width: 110px;">
                                        <input type="text" name="to_date" id="to_date"
                                               class="form-control datepicker" placeholder="Đến ngày"
                                               value="{{ request('to_date', $toDate) }}" style="min-width: 110px;">
                                    </div>

                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-primary" type="submit" style="padding: 0.45rem 1rem;">Tìm</button>
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
                            <th>Chế độ & RAG Docs</th>
                            <th>Ngày tạo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($llmLogs as $key => $llmLog)
                            @php
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

                                    {{-- Modal hiển thị full Prompt đã được làm đẹp --}}
                                    <div class="modal fade" id="promptModal{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Prompt chi tiết (#{{ $modalId }})</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <pre style="background: #272822; color: #f8f8f2; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: auto; white-space: pre-wrap; font-family: Consolas, monospace;">{{ formatBeautifulJson($llmLog['prompt'] ?? '') }}</pre>
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

                                    {{-- Modal hiển thị full Response đã được làm đẹp --}}
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

                                                    <pre style="background: #272822; color: #f8f8f2; padding: 15px; border-radius: 5px; max-height: 500px; overflow-y: auto; white-space: pre-wrap; font-family: Consolas, monospace;">{{ formatBeautifulJson($llmLog['response'] ?? '') }}</pre>
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

                                {{-- Chế độ & RAG Docs + Modal RAG tương ứng --}}
                                <td>
                                    @php
                                        $ragDocs = $llmLog['rag_documents'] ?? null;
                                        if (is_string($ragDocs)) {
                                            $ragDocs = json_decode($ragDocs, true);
                                        }
                                    @endphp

                                    @if(!empty($ragDocs))
                                        <span class="badge bg-success mb-1">RAG ({{ count($ragDocs) }} docs)</span><br>
                                        <button type="button"
                                                class="btn btn-sm btn-outline-info p-0 px-1 show_modal"
                                                data-bs-toggle="modal"
                                                data-bs-target="#ragModal{{ $modalId }}">
                                            Xem tài liệu cung cấp
                                        </button>

                                        {{-- MODAL HIỂN THỊ TÀI LIỆU RAG --}}
                                        <div class="modal fade" id="ragModal{{ $modalId }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light">
                                                        <h5 class="modal-title">Tài liệu RAG cung cấp cho LLM (#{{ $modalId }})</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body bg-dark text-light">
                                                        <p class="text-info">Tổng số tài liệu trích xuất: <strong>{{ count($ragDocs) }}</strong></p>
                                                        <hr class="border-secondary">

                                                        @foreach($ragDocs as $docIndex => $doc)
                                                            <div class="card mb-3 bg-secondary text-light border-0">
                                                                <div class="card-header bg-dark d-flex justify-content-between align-items-center py-1">
                                                                    <span><strong>#{{ $docIndex + 1 }}</strong> | Score/RRF: <span class="badge bg-warning text-dark">{{ $doc['final_score'] ?? ($doc['score'] ?? 'N/A') }}</span></span>
                                                                    <small class="text-muted">ID: {{ $doc['id'] ?? ($doc['chunk_id'] ?? 'N/A') }}</small>
                                                                </div>
                                                                <div class="card-body">
                                                                    <pre style="background: #1e1e1e; color: #d4d4d4; padding: 10px; border-radius: 4px; white-space: pre-wrap; margin: 0; font-family: Consolas, monospace;">{{ is_array($doc) ? json_encode($doc, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $doc }}</pre>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">Normal / Non-RAG</span>
                                    @endif
                                </td>

                                {{-- Ngày tạo --}}
                                <td>
                                    <small>{{ $llmLog['created_at'] ?? '-' }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">Không tìm thấy bản ghi log nào trong khoảng thời gian này.</td>
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
