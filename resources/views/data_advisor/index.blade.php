@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Dữ liệu tuyển sinh</h2>
                        <div class="d-flex align-items-center gap-2">
                            <form id="filter-data-advisor" action="{{ route('data-advisor.index') }}" method="GET" class="d-flex gap-2 align-items-center mb-0 w-100">
                                <div class="">
                                    <input type="text" name="school" class="form-control" placeholder="Tên trường" value="{{ request('school') }}">
                                </div>

                                <div class="">
                                    <input type="text" name="major" class="form-control" placeholder="Tên ngành" value="{{ request('major') }}">
                                </div>

                                <select name="year" class="form-control w-auto" style="min-width: 180px;">
                                    <option value="">Tất cả năm</option>

                                    @foreach($years as $year)
                                        <option value="{{ $year }}"
                                            {{ request('year') == $year ? 'selected' : '' }}>
                                            {{ $year }}
                                        </option>
                                    @endforeach
                                </select>

                                <div class="input-group-append">
                                    <button class="btn btn-sm btn-primary" type="submit">Tìm</button>
                                </div>
                            </form>
                        </div>
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
