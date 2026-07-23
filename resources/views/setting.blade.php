@extends('layouts.cms')

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="m-0">Cài đặt hệ thống</h2>
            </div>

            <form action="{{ route('setting.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="card border mb-4">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Ollama</h5>
                    </div>

                    <div class="card-body">

                        <div class="mb-3">
                            <label class="form-label">
                                Ollama Base URL
                            </label>

                            <input
                                type="text"
                                name="ollama_base_url"
                                class="form-control"
                                value="{{ old('ollama_base_url', $ollamaBaseUrl) }}">

                            <small class="text-muted">
                                Ví dụ: http://127.0.0.1:11434
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Chat Model
                            </label>

                            <input
                                type="text"
                                name="ollama_model"
                                class="form-control"
                                value="{{ old('ollama_model', $ollamaModel) }}">

                            <small class="text-muted">
                                Ví dụ: llama3:8b
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">
                                Embedding Model
                            </label>

                            <input
                                type="text"
                                name="ollama_model_embedding"
                                class="form-control"
                                value="{{ old('ollama_model_embedding', $ollamaEmbedding) }}">

                            <small class="text-muted">
                                Ví dụ: nomic-embed-text
                            </small>
                        </div>

                    </div>
                </div>

                <div class="text-center">
                    <button class="btn btn-primary px-4">
                        <i class="mdi mdi-content-save"></i>
                        <span class="ms-1">Lưu cấu hình</span>
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
