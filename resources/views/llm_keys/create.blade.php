@extends('layouts.cms')
@section('content')
    @php
        $breadcrumbItems = [
            [
                'label' => 'LLM Keys',
                'href' => route('llm-keys.index'),
            ],
            [
                'label' => 'Thêm key',
            ],
        ];
    @endphp

    <div class="card">
        <div class="card-body">
            <x-nav-breadcrumb :items="$breadcrumbItems"/>
            <div class="position-relative">
                <div class="mb-3 mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Thêm key</h2>
                    </div>
                </div>
            </div>

            <form action="{{ route('llm-keys.store') }}" method="POST" class="p-3 rounded shadow-sm bg-light">
                @csrf
                <div class="form-group">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">Model</label>
                    <input type="text" name="model" class="form-control">
                </div>

                <div class="form-group">
                    <label class="form-label">API Key</label>
                    <input type="text" name="api_key" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Quota Limit (If any)</label>
                    <input type="number" name="quota_limit" class="form-control">
                </div>

                <div class="d-flex justify-content-start mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="mdi mdi-content-save"></i>
                        <span class="ms-1">Thêm key</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
