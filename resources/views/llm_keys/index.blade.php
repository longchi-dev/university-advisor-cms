@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Image Keys</h2>
                        <div class="d-flex align-items-center gap-2">
                            <form id="filter-llm-keys" method="GET" class="d-flex gap-2 align-items-center mb-0 w-100">
                                <div>
                                    <input type="text" name="key_name" class="form-control"
                                           placeholder="Tên key" value="{{ old('key_name', $keyName) }}">
                                </div>

                                <div>
                                    <input type="text" name="last_used_at" id="last_used_date"
                                           class="form-control datepicker" placeholder="Lần cuối sử dụng"
                                           value="{{ request('last_used_at') }}">
                                </div>
{{--                                <div class="form-group d-flex gap-2 flex-fill mb-0">--}}
{{--                                    <input type="text" name="from_date" id="from_date"--}}
{{--                                           class="form-control datepicker" placeholder="Từ ngày"--}}
{{--                                           value="{{ request('from_date', now()->format('d-m-Y')) }}">--}}
{{--                                    <input type="text" name="to_date" id="to_date"--}}
{{--                                           class="form-control datepicker" placeholder="Đến ngày"--}}
{{--                                           value="{{ request('to_date', now()->format('d-m-Y')) }}">--}}
{{--                                </div>--}}

                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary" type="submit">Tìm</button>
                                </div>

                                <a href="{{ route('llm-keys.create') }}" class="btn btn-success btn-sm create-reward-btn px-4">
                                    <span class="create-reward-text">Thêm key</span>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>API Key</th>
                        <th>Active</th>
                        <th>Used Tokens</th>
                        <th>Quota Limit (If any)</th>
                        <th>Lần cuối sử dụng</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($keys as $key)
                        <tr>
                            <td>{{ $key->id }}</td>
                            <td>{{ $key->name }}</td>
                            <td><code>{{ $key->api_key }}</code></td>
                            <td>
                                <label class="badge badge-{{ $key->is_active ? 'success' : 'secondary' }}">
                                    {{ $key->is_active ? 'Yes' : 'No' }}
                                </label>
                            </td>
                            <td>{{ $key->used_tokens }}</td>
                            <td>{{ $key->quota_limit ?? '-' }}</td>
                            <td>{{ $key->last_used_at ?? '-' }}</td>
{{--                            <td>--}}

{{--                                <a href="{{ route('llm-keys.edit', $key) }}" class="btn btn-sm btn-warning">--}}
{{--                                    <i class="mdi mdi-pencil"></i>--}}
{{--                                </a>--}}

{{--                            </td>--}}

                            <td>
                                <a href="{{ route('llm-keys.edit', $key) }}"
                                   class="btn btn-warning btn-icon d-flex align-items-center justify-content-center"
                                   style="width: 36px; height: 36px;"
                                   title="Sửa quà">
                                    <i class="mdi mdi-pencil"></i>
                                </a>

                                {{--                                <form action="{{ route('llm-keys.destroy', $key) }}" method="POST" class="d-inline">--}}
                                {{--                                    @csrf @method('DELETE')--}}
                                {{--                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this key?')">--}}
                                {{--                                        <i class="mdi mdi-delete"></i>--}}
                                {{--                                    </button>--}}
                                {{--                                </form>--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <x-pagination :data="$keys" />
        </div>
    </div>
@endsection
