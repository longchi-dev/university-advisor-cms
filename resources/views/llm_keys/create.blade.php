@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('llm-keys.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control">
                </div>

                <div class="form-group">
                    <label>API Key</label>
                    <input type="text" name="api_key" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Quota Limit (If any)</label>
                    <input type="number" name="quota_limit" class="form-control">
                </div>

                <button class="btn btn-success"><i class="mdi mdi-content-save"></i> Save</button>
                <a href="{{ route('llm-keys.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
@endsection
