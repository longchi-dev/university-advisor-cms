@extends('layouts.cms')
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Prompt Randoms</h2>
                        <div class="d-flex align-items-center gap-2">
                            <form id="filter-prompt-randoms" method="GET" class="d-flex gap-2 align-items-center mb-0 w-100">
                                <div class="d-flex align-items-center gap-2">
{{--                                    <div class="form-group d-flex gap-2 flex-fill mb-0">--}}
{{--                                        <input type="text" name="from_date" id="from_date"--}}
{{--                                               class="form-control datepicker" placeholder="Từ ngày"--}}
{{--                                               value="{{ request('from_date', now()->format('d-m-Y')) }}">--}}
{{--                                        <input type="text" name="to_date" id="to_date"--}}
{{--                                               class="form-control datepicker" placeholder="Đến ngày"--}}
{{--                                               value="{{ request('to_date', now()->format('d-m-Y')) }}">--}}
{{--                                    </div>--}}
                                    <div class="form-group d-flex gap-2 flex-fill mb-0">
                                        <select name="group" class="form-control">
                                            <option value="">Chọn group</option>
                                            @foreach(\App\Enums\RandomGroupEnum::values() as $enumGr)
                                                <option value="{{ $enumGr }}" {{ $group == $enumGr ? "selected" : "" }} >{{ $enumGr }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="input-group-append">
                                        <button class="btn btn-sm btn-primary" type="submit">Tìm</button>
                                    </div>

                                    <a href="{{ route('prompt-randoms.create', ['group' => $group]) }}" class="btn btn-success btn-sm create-reward-btn px-4">
                                        <span class="create-reward-text">Thêm prompt</span>
                                    </a>
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
                            <th>Group</th>
                            <th>Value</th>
                            <th>Weight</th>
                            <th>Active</th>
                            <th>Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($randoms as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td><code>{{ $item->group }}</code></td>
{{--                                    <td>{{ $item->value }}</td>--}}

                                    <td style="vertical-align: top;">
                                        <div style="max-width: 500px; white-space: normal; overflow: hidden; text-overflow: ellipsis;">
                                            {{ Str::limit($item->value, 250) ?? '-' }}
                                        </div>
                                        @if(strlen($item->value) > 250)
                                            <button type="button"
                                                    class="btn btn-sm btn-link p-0 show_modal"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#responseModal{{ $item->id }}">
                                                Xem
                                            </button>
                                        @endif

                                        @php
                                            $response = $item->value;
                                            $json = json_decode($response, true);
                                        @endphp

                                        {{-- Modal hiển thị full Response --}}
                                        <div class="modal fade" id="responseModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Value (ID: {{ $item->id }})</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{ $json? json_encode($json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $response }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td>{{ $item->weight }}</td>

                                    <td>
                                        <label class="badge badge-{{ $item->is_active ? 'success' : 'secondary' }}">
                                            {{ $item->is_active ? 'Yes' : 'No' }}
                                        </label>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('prompt-randoms.edit', $item) }}"
                                               class="btn btn-warning btn-icon d-flex align-items-center justify-content-center"
                                               style="width: 36px; height: 36px;"
                                               title="Sửa">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>

                                            <form method="POST"
                                                  action="{{ route('prompt-randoms.destroy', $item) }}"
                                                  onsubmit="return confirm('Delete?')">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-reddit btn-icon d-flex align-items-center justify-content-center"
                                                        style="width: 36px; height: 36px;">
                                                    <i class="mdi mdi-trash-can"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <x-pagination :data="$randoms" />
        </div>
    </div>

{{--    <form method="GET" class="form-inline mb-3">--}}
{{--        <label class="mr-2">Group</label>--}}
{{--        <select name="group" class="form-control mr-2" required>--}}
{{--            <option value="">Chọn group</option>--}}
{{--            @foreach(\App\Enums\RandomGroupEnum::values() as $enumGr)--}}
{{--                <option value="{{ $enumGr }}" {{ $group == $enumGr ? "selected" : "" }} >{{ $enumGr }}</option>--}}
{{--            @endforeach--}}
{{--        </select>--}}
{{--        <button class="btn btn-info">Filter</button>--}}
{{--    </form>--}}
@endsection

@push('js')
    <script>
        document.addEventListener('show.bs.modal', function (event) {
            const modal = event.target;
            document.body.appendChild(modal);
        });
    </script>
@endpush
