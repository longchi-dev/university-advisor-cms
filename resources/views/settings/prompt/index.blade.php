@extends('layouts.cms')

@section('content')
    <div class="container-fluid">

        <h2 class="mb-4">Cài đặt System Prompt</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @foreach($prompts as $prompt)
            <div class="card shadow-sm mb-4">

                <div class="card-header">
                    <strong>{{ $prompt->type->value }}</strong>
                </div>

                <div class="card-body">

                    <form method="POST" action="{{ route('setting-prompt.update', $prompt) }}">
                        @csrf
                        @method('PATCH')

                        <textarea
                            name="prompt"
                            rows="15"
                            class="form-control font-monospace"
                        >{{ old('prompt', $prompt->prompt) }}</textarea>

                        @error('prompt')
                        <div class="text-danger mt-2">
                            {{ $message }}
                        </div>
                        @enderror

                        <button type="submit" class="btn btn-primary mt-3">
                            Lưu Prompt
                        </button>
                    </form>

                </div>
            </div>
        @endforeach

    </div>
@endsection
