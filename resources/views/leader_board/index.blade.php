@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Bảng xếp hạng</h2>
                        <div class="d-flex align-items-center gap-2">
                            <form id="filter-players" method="GET" class="d-flex gap-2 align-items-center mb-0 w-100">
                                <div class="form-group d-flex gap-2 flex-fill mb-0">
                                    <select name="week_number" class="form-control flex-grow-1">
                                        <option value="">Tất cả tuần</option>
                                        @for($i = 1; $i <= $maxWeek; $i++)
                                            <option value="{{ $i }}" {{ request('week_number') == $i ? 'selected' : '' }}>
                                                Tuần {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>

                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary" type="submit">Tìm</button>
                                </div>

                                <div class="d-flex align-items-center gap-2">
                                    {{-- Import --}}
                                    <button type="button" class="btn btn-success btn-sm import-btn px-3" onclick="triggerImport()">
                                        <span class="me-2">📥</span>
                                        <span class="import-text">Import Data</span>
                                    </button>

                                    {{-- File mẫu --}}
                                    <a href="/storage/imports/sample_leaderboard.xlsx" class="btn btn-secondary btn-sm" download>
                                        📄 Mẫu
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">
                            ⚠️ Import sẽ ghi đè dữ liệu của tuần hiện tại
                        </small>
                    </div>
                </div>
                <input type="file" id="importFile" accept=".xlsx" hidden />

            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Xếp hạng</th>
                            <th>Tên người chơi</th>
                            <th>Số điện thoại</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($leaderBoards as $key => $leaderBoard)
                            <tr>
                                <td>{{ $leaderBoard['rank'] }}</td>
                                <td>{{ $leaderBoard['player_name'] }}</td>
                                <td>{{ $leaderBoard['phone'] }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <x-pagination :data="$leaderBoards" />
        </div>
    </div>
@endsection
@push('js')
    <script>
        let importJobId = null;

        function triggerImport() {
            document.getElementById('importFile').click();
        }

        document.getElementById('importFile').addEventListener('change', async function (e) {
            const file = e.target.files[0];
            if (!file) return;

            const btn = document.querySelector('.import-btn');
            const text = btn.querySelector('.import-text');

            const formData = new FormData();
            formData.append('file', file);

            btn.disabled = true;
            text.textContent = 'Uploading...';

            try {
                const response = await fetch('/api/import-leaderboard-data', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Import failed');
                }

                importJobId = data.job_id;

                text.textContent = 'Processing...';

                checkImportStatus();

            } catch (error) {
                console.error(error);
                showNotification(error.message, 'error');
                resetImportBtn();
            }
        });

        async function checkImportStatus() {
            if (!importJobId) return;

            const btn = document.querySelector('.import-btn');
            const text = btn.querySelector('.import-text');

            try {
                const response = await fetch(`/api/import-leaderboard-data/status/${importJobId}`, {
                    headers: { 'Accept': 'application/json' }
                });

                const data = await response.json();

                if (!response.ok) {
                    throw new Error(data.message || 'Check failed');
                }

                if (data.status === 'completed') {
                    showNotification('Import completed!', 'success');
                    resetImportBtn();
                    setTimeout(() => location.reload(), 1000);
                } else if (data.status === 'failed') {
                    throw new Error(data.error || 'Import failed');

                } else {
                    text.textContent = `Processing... (${data.processed || 0})`;
                    setTimeout(checkImportStatus, 2000);
                }

            } catch (error) {
                console.error(error);
                showNotification(error.message, 'error');
                resetImportBtn();
            }
        }

        function resetImportBtn() {
            const btn = document.querySelector('.import-btn');
            const text = btn.querySelector('.import-text');

            btn.disabled = false;
            text.textContent = 'Import Data';
            importJobId = null;
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
