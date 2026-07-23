@extends('layouts.cms')

@section('content')
    <div class="card shadow-sm border-0">

        <div class="card-body">

            <div class="mb-4">
                <h2 class="mb-1">
                    Cài đặt hệ thống
                </h2>

                <p class="text-muted mb-0">
                    Quản lý cấu hình AI và Ollama cho hệ thống.
                </p>
            </div>


            <form action="{{ route('setting.update') }}" method="POST">

                @csrf
                @method('PATCH')


                <div class="card border mb-4">

                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            Ollama Configuration
                        </h5>
                    </div>


                    <div class="card-body">


                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Ollama Base URL
                            </label>


                            <input
                                type="text"
                                name="ollama_base_url"
                                class="form-control"
                                value="{{ old('ollama_base_url', $ollamaBaseUrl) }}"
                                placeholder="http://127.0.0.1:11434"
                            >


                            <small class="text-muted">
                                Địa chỉ Ollama server.
                            </small>

                        </div>



                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Chat Model
                            </label>


                            <input
                                type="text"
                                name="ollama_model"
                                class="form-control"
                                value="{{ old('ollama_model', $ollamaModel) }}"
                                placeholder="llama3:8b"
                            >


                            <small class="text-muted">
                                Model dùng cho sinh câu trả lời.
                            </small>

                        </div>




                        <div class="mb-3">

                            <label class="form-label fw-semibold">
                                Embedding Model
                            </label>


                            <input
                                type="text"
                                name="ollama_model_embedding"
                                class="form-control"
                                value="{{ old('ollama_model_embedding', $ollamaEmbedding) }}"
                                placeholder="nomic-embed-text"
                            >


                            <small class="text-muted">
                                Model dùng để tạo vector embedding.
                            </small>

                        </div>


                    </div>

                </div>



                <div class="d-flex justify-content-center">

                    <button
                        type="submit"
                        class="btn btn-primary px-5"
                    >

                        <i class="mdi mdi-content-save me-1"></i>

                        Lưu cấu hình

                    </button>

                </div>


            </form>


        </div>

    </div>
@endsection
