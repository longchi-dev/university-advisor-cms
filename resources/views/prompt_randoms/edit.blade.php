@extends('layouts.cms')
@section('content')
    <h4 class="mb-3">Edit Prompt Random</h4>

    <form method="POST"
          action="{{ route('prompt-randoms.update', $promptRandom) }}">
        @csrf
        @method('PUT')

        @include('prompt_randoms.form', ['item' => $promptRandom])

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('prompt-randoms.index', ['group' => $promptRandom->group]) }}"
           class="btn btn-secondary">
            Cancel
        </a>
    </form>
@endsection

