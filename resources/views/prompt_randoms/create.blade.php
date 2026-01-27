@extends('layouts.cms')
@section('content')
    <h4 class="mb-3">Create Prompt Random</h4>

    <form method="POST" action="{{ route('prompt-randoms.store') }}">
        @csrf

        @include('prompt_randoms.form')

        <button class="btn btn-primary">Save</button>
        <a href="{{ route('prompt-randoms.index', ['group' => $group]) }}"
           class="btn btn-secondary">
            Cancel
        </a>
    </form>
@endsection
