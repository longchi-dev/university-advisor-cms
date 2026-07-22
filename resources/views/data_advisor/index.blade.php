@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Dữ liệu tuyển sinh</h2>
                        <div class="d-flex align-items-center gap-2">
                            {{-- Nút mở bộ lọc --}}
                            <button class="btn btn-sm text-dark btn-outline-secondary d-flex align-items-center"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#filterCollapse"
                                    aria-expanded="false"
                                    aria-controls="filterCollapse">
                                <span class="me-1">🎯</span> Bộ lọc
                            </button>

                            {{-- Nút Export --}}
                            <button type="submit" class="btn btn-success btn-sm export-btn px-4"
                                    onclick="startExport()">
                                <span class="me-2">📥</span>
                                <span class="export-text">Download</span>
                                <div class="spinner-border spinner-border-sm d-none" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <div class="collapse mt-3" id="filterCollapse">
                        <form class="p-3 rounded shadow-sm bg-light">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input
                                        type="number"
                                        name="year"
                                        class="form-control"
                                        placeholder="Năm"
                                        value="{{ request('year') }}"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <input
                                        type="text"
                                        name="school"
                                        class="form-control"
                                        placeholder="Tên trường"
                                        value="{{ request('school') }}"
                                    >
                                </div>

                                <div class="col-md-4">
                                    <input
                                        type="text"
                                        name="major"
                                        class="form-control"
                                        placeholder="Tên ngành"
                                        value="{{ request('major') }}"
                                    >
                                </div>

                            </div>


                            <div class="text-center mt-3">
                                <button class="btn btn-primary px-4">
                                    Tìm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="table-responsive">
                    <table class="table">

                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Trường</th>
                            <th>Ngành</th>
                            <th>Năm</th>
                            <th>Điểm</th>
                            <th>Khối</th>
                            <th>Hệ</th>
                        </tr>
                        </thead>


                        <tbody>

                        @foreach($scores as $key => $score)
                            <tr>
                                <td>{{ $scores->firstItem() + $key }}</td>
                                <td>{{ $score->school->name }}</td>
                                <td>{{ $score->major->name }}</td>
                                <td>{{ $score->year }}</td>
                                <td>{{ $score->score }}</td>
                                <td>{{ $score->block }}</td>
                                <td>{{ $score->level }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <x-pagination :data="$scores"/>
        </div>
    </div>
@endsection
