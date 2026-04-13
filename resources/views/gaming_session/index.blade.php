@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Lượt chơi</h2>
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
                        <form id="filter-gaming-session" class="p-3 rounded shadow-sm bg-light">
                            {{-- Hàng 1 --}}
                            <div class="flex-wrap gap-2 mb-3 w-100">
                                {{-- Filter theo theme --}}
                                <select name="theme_id" id="theme_id" class="form-control flex-grow-1 mb-3" style="min-width:180px;">
                                    <option value="">Tất cả theme</option>
                                    @foreach($themes as $theme)
                                        <option value="{{ $theme->id }}" {{ request('theme_id') == $theme->id ? 'selected' : '' }}>
                                            {{ $theme->name }}
                                        </option>
                                    @endforeach
                                </select>

                                <select name="has_outcome" id="has_outcome" class="form-control flex-grow-1 mb-3">
                                    <option value="">Có ảnh outcome không?</option>
                                    <option value="1" {{ request('has_outcome') === '1' ? 'selected' : '' }}>Có</option>
                                    <option value="0" {{ request('has_outcome') === '0' ? 'selected' : '' }}>Không</option>
                                </select>

                                <div class="d-flex gap-2 mb-3 w-100">
                                    <div class="form-group flex-fill mb-0">
                                        <select name="is_shared_fb" id="is_shared_fb" class="form-control">
                                            <option value="">Có share fb không?</option>
                                            <option value="1" {{ request('is_shared_fb') === '1' ? 'selected' : '' }}>Có</option>
                                            <option value="0" {{ request('is_shared_fb') === '0' ? 'selected' : '' }}>Không</option>
                                        </select>
                                    </div>

                                    <div class="form-group flex-fill mb-0">
                                        <select name="is_shared_ig" id="is_shared_ig" class="form-control">
                                            <option value="">Có share ig không?</option>
                                            <option value="1" {{ request('is_shared_ig') === '1' ? 'selected' : '' }}>Có</option>
                                            <option value="0" {{ request('is_shared_ig') === '0' ? 'selected' : '' }}>Không</option>
                                        </select>
                                    </div>

                                    <div class="form-group flex-fill mb-0">
                                        <select name="is_saved" id="is_saved" class="form-control">
                                            <option value="">Có lưu ảnh không?</option>
                                            <option value="1" {{ request('is_saved') === '1' ? 'selected' : '' }}>Có</option>
                                            <option value="0" {{ request('is_saved') === '0' ? 'selected' : '' }}>Không</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Hàng 2 --}}
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-3 w-100">
                                <div class="border rounded p-3 bg-white shadow-sm flex-grow-1">
                                    <label class="fw-bold mb-2 d-block text-center">Thời gian bắt đầu</label>
                                    <div class="d-flex flex-wrap gap-2 justify-content-center">
                                        <input type="text" class="form-control datepicker" name="from_date"
                                               value="{{ $fromDate }}" placeholder="Từ ngày">
                                        <input type="text" class="form-control datepicker" name="to_date"
                                               value="{{ $toDate }}" placeholder="Đến ngày">
                                    </div>
                                </div>
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
                                <th>STT</th>
                                <th>IP</th>
                                <th>Tên người chơi</th>
                                <th>Full url</th>
                                <th>Chủ đề</th>
                                <th>Ảnh upload</th>
                                <th>Ảnh outcome</th>
                                <th>Thời gian bắt đầu</th>
                                <th>Thời gian kết thúc</th>
                                <th>Chia sẻ fb lúc</th>
                                <th>Chia sẻ ig lúc</th>
                                <th>Lưu ảnh lúc</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($gamingSessions as $key => $gamingSession)
                                <tr>
                                    <td>{{ $gamingSessions->firstItem() + $key }}</td>
                                    <td>{{ $gamingSession['ip_address'] }}</td>
                                    <td>{{ $gamingSession['player_first_name'] }}</td>
                                    <td>{{ $gamingSession['full_url'] }}</td>
                                    <td>{{ $gamingSession['theme_label'] }}</td>
                                    {{-- Ảnh upload gốc --}}
                                    <td>
                                        <a target="_blank" href="{{ $gamingSession['upload'] }}">
                                            <img src="{{ $gamingSession['upload'] }}"
                                                 alt="Ảnh upload"
                                                 class="img-thumbnail rounded"
                                                 style="width: 100px; height: auto; object-fit: cover;">
                                        </a>
                                    </td>

                                    {{-- Ảnh có khung --}}
                                    <td>
                                        <a target="_blank" href="{{ $gamingSession['image_has_frame'] }}">
                                            <img src="{{ $gamingSession['image_has_frame'] }}"
                                                 alt="Ảnh có khung"
                                                 class="img-thumbnail rounded border-success"
                                                 style="width: 100px; height: auto; object-fit: cover;">
                                        </a>
                                    </td>

                                    {{-- Thời gian bắt đầu --}}
                                    <td>{{ $gamingSession['started_at'] }}</td>
                                    {{-- Thời gian kết thúc --}}
                                    <td>{{ $gamingSession['finished_at'] }}</td>
                                    {{-- Thời gian share và lưu --}}
                                    <td>{{ $gamingSession['share_fb_at'] }}</td>
                                    <td>{{ $gamingSession['share_ig_at'] }}</td>
                                    <td>{{ $gamingSession['save_at'] }}</td>

                                    {{-- Hành động --}}
{{--                                    <td>--}}
{{--                                        <div class="d-flex gap-1">--}}
{{--                                            <a href="{{ route('gaming-session.show', ['sessionId' => $gamingSession['id']]) }}"--}}
{{--                                               class="btn btn-primary btn-icon d-flex align-items-center justify-content-center me-1"--}}
{{--                                               style="width: 36px; height: 36px;"--}}
{{--                                               title="Xem chi tiết">--}}
{{--                                                <i class="mdi mdi-eye"></i>--}}
{{--                                            </a>--}}
{{--                                        </div>--}}
{{--                                    </td>--}}
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <x-pagination :data="$gamingSessions" />
        </div>
    </div>
@endsection
@push('js')
    <script>
        // EXPORT CSV
        async function startExport() {
            const btn = document.querySelector('.export-btn');
            const exportText = btn.querySelector('.export-text');
            const spinner = btn.querySelector('.spinner-border');
            const form = document.getElementById('filter-gaming-session');

            // Lấy giá trị form
            const themeId = form.querySelector('select[name="theme_id"]')?.value || null;
            const isSharedFb = form.querySelector('select[name="is_shared_fb"]')?.value || null;
            const isSharedIg = form.querySelector('select[name="is_shared_ig"]')?.value || null;
            const isSaved = form.querySelector('select[name="is_saved"]')?.value || null;
            const fromDate = form.querySelector('input[name="from_date"]')?.value || null;
            const toDate = form.querySelector('input[name="to_date"]')?.value || null;

            // Disable button and show loading
            btn.disabled = true;
            exportText.textContent = 'Starting Export...';
            spinner.classList.remove('d-none');

            try {
                // Call export API
                const response = await fetch('/api/export-gaming-session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        theme_id: themeId,
                        is_shared_fb: isSharedFb,
                        is_shared_ig: isSharedIg,
                        is_saved: isSaved,
                        from_date: fromDate,
                        to_date: toDate,
                    })
                });

                if (response.status === 401) {
                    throw new Error('Please login again');
                }

                if (!response.ok) {
                    const errorData = await response.json().catch(() => ({}));
                    throw new Error(errorData.message || 'Failed to start export');
                }

                const data = await response.json();
                exportJobId = data.job_id;

                // Start checking status
                checkExportStatus();

            } catch (error) {
                console.error('Export error:', error);
                resetExportButton();
                showNotification(error.message || 'Failed to start export. Please try again.', 'error');
            }
        }

        async function checkExportStatus() {
            if (!exportJobId) return;

            const btn = document.querySelector('.export-btn');
            const exportText = btn.querySelector('.export-text');

            try {
                const response = await fetch(`/api/export-gaming-session/status/${exportJobId}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (response.status === 401) {
                    throw new Error('Session expired. Please login again.');
                }

                if (!response.ok) {
                    throw new Error('Failed to check export status');
                }

                const data = await response.json();

                if (data.status === 'completed') {
                    // Export completed, download file
                    exportText.textContent = 'Downloading...';
                    await downloadFile(data.file_url);
                    resetExportButton();
                    showNotification('Export completed and downloaded successfully!', 'success');

                } else if (data.status === 'failed') {
                    throw new Error('Export failed on server');

                } else {
                    // Still processing, update progress
                    const progress = data.total > 0 ? Math.round((data.processed / data.total) * 100) : 0;
                    exportText.textContent = `Processing... ${progress}% (${data.processed}/${data.total})`;

                    // Check again after 2 seconds
                    setTimeout(checkExportStatus, 2000);
                }

            } catch (error) {
                console.error('Status check error:', error);
                resetExportButton();
                showNotification(error.message || 'Export failed. Please try again.', 'error');
            }
        }

        async function downloadFile(fileUrl) {
            try {
                // Create a temporary link and trigger download
                const link = document.createElement('a');
                link.href = fileUrl;
                // link.download = `gaming-session-export-${exportJobId}.csv`;
                link.style.display = 'none';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

            } catch (error) {
                console.error('Download error:', error);
                // Fallback: open in new window
                window.open(fileUrl, '_blank');
            }
        }

        function resetExportButton() {
            const btn = document.querySelector('.export-btn');
            const exportText = btn.querySelector('.export-text');
            const spinner = btn.querySelector('.spinner-border');

            btn.disabled = false;
            exportText.textContent = 'Download CSV';
            spinner.classList.add('d-none');
            exportJobId = null;
        }

        // SHOW NOTIFICATION
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;

            document.body.appendChild(notification);

            // Auto remove after 5 seconds
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 5000);
        }

        // Add CSRF token to meta tag if not exists (fallback)
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const csrfMeta = document.createElement('meta');
            csrfMeta.name = 'csrf-token';
            csrfMeta.content = '{{ csrf_token() }}';
            document.head.appendChild(csrfMeta);
        }
    </script>
@endpush
