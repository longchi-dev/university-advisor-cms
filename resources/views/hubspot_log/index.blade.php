@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Hubspot Logs</h2>
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
                            <th>Service</th>
                            <th>Action</th>
                            <th>Endpoint</th>
                            <th>Request</th>
                            <th>Response</th>
                            <th>Status</th>
                            <th>Kết quả</th>
                            <th>Requested At</th>
                            <th>Responded At</th>
                            <th>Exec Time (s)</th>
                            <th>Ngày tạo</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($hubspotLogs as $key => $log)
                            <tr>
                                <td>{{ $hubspotLogs->firstItem() + $key }}</td>

                                {{-- Service --}}
                                <td>
                                    <span class="badge badge-info">{{ $log->service }}</span>
                                </td>

                                {{-- Action --}}
                                <td>
                                    <span class="badge badge-primary">{{ $log->action }}</span>
                                </td>

                                {{-- Endpoint --}}
                                <td style="max-width: 200px;">
                                    <div style="white-space: normal;">
                                        {{ $log->endpoint }}
                                    </div>
                                </td>

                                {{-- Request payload --}}
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-link p-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#requestModal{{ $log->id }}">
                                        Xem
                                    </button>

                                    <div class="modal fade" id="requestModal{{ $log->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Request (ID: {{ $log->id }})</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <pre>{{ json_encode($log->request_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Response payload --}}
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-link p-0"
                                            data-bs-toggle="modal"
                                            data-bs-target="#responseModal{{ $log->id }}">
                                        Xem
                                    </button>

                                    <div class="modal fade" id="responseModal{{ $log->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Response (ID: {{ $log->id }})</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <pre>{{ json_encode($log->response_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- Status code --}}
                                <td>
                        <span class="badge badge-{{ $log->status_code >= 400 ? 'danger' : 'success' }}">
                            {{ $log->status_code ?? '-' }}
                        </span>
                                </td>

                                {{-- is_success --}}
                                <td>
                        <span class="badge badge-{{ $log->is_success ? 'success' : 'danger' }}">
                            {{ $log->is_success ? 'Success' : 'Fail' }}
                        </span>
                                </td>

                                {{-- Requested At --}}
                                <td>
                                    {{ $log->requested_at
                                        ? \Carbon\Carbon::parse($log->requested_at)->format('d/m/Y H:i:s')
                                        : '-' }}
                                </td>

                                {{-- Responded At --}}
                                <td>
                                    {{ $log->responded_at
                                        ? \Carbon\Carbon::parse($log->responded_at)->format('d/m/Y H:i:s')
                                        : '-' }}
                                </td>

                                {{-- Exec Time --}}
                                <td>
                                    @if($log->requested_at && $log->responded_at)
                                        @php
                                            $time = \Carbon\Carbon::parse($log->requested_at)
                                                ->diffInMilliseconds(\Carbon\Carbon::parse($log->responded_at)) / 1000;
                                        @endphp
                                        <span class="badge badge-{{ $time > 5 ? 'danger' : ($time > 2 ? 'warning' : 'success') }}">
                                            {{ number_format($time, 2) }}s
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                {{-- Created --}}
                                <td>
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i:s') }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <x-pagination :data="$hubspotLogs" />
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
