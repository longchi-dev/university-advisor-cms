@extends('layouts.cms')

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('gemini-keys.update', $llmKey) }}" method="POST">
                @csrf @method('PUT')

                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $llmKey->name) }}">
                </div>

                <div class="form-group">
                    <label>API Key</label>
                    <input type="text" name="api_key" class="form-control" value="{{ old('api_key', $llmKey->api_key) }}" required>
                </div>

                <div class="form-group">
                    <label>Quota Limit (If any)</label>
                    <input type="number" name="quota_limit" class="form-control" value="{{ old('quota_limit', $llmKey->quota_limit) }}">
                </div>

                <div class="form-group">
                    <label>Active</label>
                    <select name="is_active" class="form-control">
                        <option value="1" @if($llmKey->is_active) selected @endif>Yes</option>
                        <option value="0" @if(!$llmKey->is_active) selected @endif>No</option>
                    </select>
                </div>

                <button class="btn btn-success"><i class="mdi mdi-content-save"></i> Update</button>
                <a href="{{ route('gemini-keys.index') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
@endsection
