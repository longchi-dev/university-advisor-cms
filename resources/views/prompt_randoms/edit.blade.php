@extends('layouts.cms')
@section('content')
    @php
        $breadcrumbItems = [
            [
                'label' => 'Prompt Randoms',
                'href' => route('prompt-randoms.index', ['group' => $promptRandom->group]),
            ],
            [
                'label' => 'Cập nhật prompt',
            ],
        ];
    @endphp

    <div class="card">
        <div class="card-body">
            <x-nav-breadcrumb :items="$breadcrumbItems"/>
            <div class="position-relative">
                <div class="mb-3 mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Cập nhật prompt</h2>
                    </div>
                </div>
            </div>

            <form action="{{ route('prompt-randoms.update', $promptRandom) }}" method="POST">
                @csrf @method('PUT')
                @include('prompt_randoms.form', ['item' => $promptRandom])

                <div class="d-flex justify-content-start mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="mdi mdi-content-save"></i>
                        <span class="ms-1">Cập nhật key</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

