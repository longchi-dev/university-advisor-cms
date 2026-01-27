@extends('layouts.cms')
@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h4>Prompt Randoms</h4>
        <a href="{{ route('prompt-randoms.create', ['group' => $group]) }}"
           class="btn btn-primary">
            + Add
        </a>
    </div>

    <form method="GET" class="form-inline mb-3">
        <label class="mr-2">Group</label>
        <select name="group" class="form-control mr-2" required>
            <option value="">Chọn group</option>
            @foreach(\App\Enums\RandomGroupEnum::values() as $enumGr)
                <option value="{{ $enumGr }}" {{ $group == $enumGr ? "selected" : "" }} >{{ $enumGr }}</option>
            @endforeach
        </select>
        <button class="btn btn-info">Filter</button>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="thead-light">
        <tr>
            <th>ID</th>
            <th>Group</th>
            <th>Value</th>
            <th width="80">Weight</th>
            <th width="80">Active</th>
            <th width="160"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($randoms as $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td><code>{{ $item->group }}</code></td>
                <td>{{ $item->value }}</td>
                <td>{{ $item->weight }}</td>
                <td>
                    @if($item->is_active)
                        <span class="badge badge-success">Yes</span>
                    @else
                        <span class="badge badge-secondary">No</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('prompt-randoms.edit', $item) }}"
                       class="btn btn-sm btn-warning">
                        Edit
                    </a>

                    <form method="POST"
                          action="{{ route('prompt-randoms.destroy', $item) }}"
                          class="d-inline"
                          onsubmit="return confirm('Delete?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $randoms->withQueryString()->links() }}
@endsection
