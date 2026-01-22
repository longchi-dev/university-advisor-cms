@extends('layouts.cms')

@section('content')
    <div class="card">
        <div class="card-body">
            <a href="{{ route('llm-keys.create') }}" class="btn btn-primary mb-3">
                <i class="mdi mdi-plus"></i> Add new key
            </a>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>API Key</th>
                        <th>Active</th>
                        <th>Used Tokens</th>
                        <th>Quota Limit (If any)</th>
                        <th>Last Used</th>
                        <th>Actions</th>
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
                            <td>
                                <a href="{{ route('llm-keys.edit', $key) }}" class="btn btn-sm btn-warning">
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
