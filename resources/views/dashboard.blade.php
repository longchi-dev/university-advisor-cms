@extends('layouts.cms')
@section('content')
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-dark font-weight-bold animate-fade-in">Dashboard Overview</h1>
                        <p class="text-muted mb-0 animate-slide-up">Welcome back! Here's what's happening with your
                            platform.
                        </p>
                    </div>
                    <div class="text-right">
                        <small class="text-muted animate-pulse">Last updated: {{ now()->format('M d, Y H:i') }}</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-5">
            <!-- Total Users Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 card-hover gradient-card-blue">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1 opacity-90">
                                    Total Users
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-white animate-count-up">
                                    {{ number_format($totalUsers) }}
                                </div>
                                <div class="text-xs text-white mt-1 opacity-80">
                                    <span class="status-indicator info"></span> Total unique players
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-white bg-opacity-20">
                                    <span class="icon-symbol">👥</span>
                                </div>
                            </div>
                        </div>
                        <div class="floating-shapes">
                            <div class="shape shape-1"></div>
                            <div class="shape shape-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Game Sessions Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 card-hover gradient-card-green">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1 opacity-90">
                                    Gaming Sessions
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-white animate-count-up">
{{--                                    {{ number_format($totalGamingSessions) }}--}}
                                </div>
                                <div class="text-xs text-white mt-1 opacity-80">
                                    <span class="status-indicator info"></span> Total gaming sessions
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-white bg-opacity-20">
                                    <span class="icon-symbol">🎮</span>
                                </div>
                            </div>
                        </div>
                        <div class="floating-shapes">
                            <div class="shape shape-1"></div>
                            <div class="shape shape-2"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Miniapp Access Card -->
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 card-hover gradient-card-orange">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1 opacity-90">
                                    Shares & Saves
                                </div>
                                <div class="row text-white">

                                    {{-- Facebook --}}
                                    <div class="col-4 text-center border-end">
                                        <div class="text-xs opacity-80">Facebook</div>
                                        <div class="h5 mb-0 font-weight-bold animate-count-up">
{{--                                            {{ number_format($totalShareFacebook) }}--}}
                                        </div>
                                    </div>

                                    {{-- Instagram --}}
                                    <div class="col-4 text-center border-end">
                                        <div class="text-xs opacity-80">Instagram</div>
                                        <div class="h5 mb-0 font-weight-bold animate-count-up">
{{--                                            {{ number_format($totalShareInstagram) }}--}}
                                        </div>
                                    </div>

                                    {{-- Save --}}
                                    <div class="col-4 text-center">
                                        <div class="text-xs opacity-80">Save</div>
                                        <div class="h5 mb-0 font-weight-bold animate-count-up">
{{--                                            {{ number_format($totalSave) }}--}}
                                        </div>
                                    </div>
                                </div>
                                <div class="text-xs text-white mt-1 opacity-80">
                                    <span class="status-indicator info"></span> Total interactions
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-white bg-opacity-20">
                                    <span class="icon-symbol">📱</span>
                                </div>
                            </div>
                        </div>
                        <div class="floating-shapes">
                            <div class="shape shape-1"></div>
                            <div class="shape shape-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

{{--        <div class="row mb-4">--}}
{{--            <div class="col-md-6 mb-3">--}}
{{--                <div class="export-card p-4 rounded border h-100 position-relative overflow-hidden">--}}
{{--                    <div class="d-flex align-items-start mb-3">--}}
{{--                        <div class="icon-sm bg-gradient-primary rounded me-3">--}}
{{--                            <span class="icon-symbol-sm">📊</span>--}}
{{--                        </div>--}}
{{--                        <div class="flex-grow-1">--}}
{{--                            <h6 class="mb-1 font-weight-bold">Activity Log Data</h6>--}}
{{--                            <p class="text-muted small mb-0">Export complete activity log data.--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <button type="button" class="btn btn-gradient-primary btn-sm w-100 export-btn mb-3"--}}
{{--                            onclick="startExport()">--}}
{{--                        <span class="me-2">📥</span>--}}
{{--                        <span class="export-text">Download CSV</span>--}}
{{--                        <div class="spinner-border spinner-border-sm d-none" role="status">--}}
{{--                            <span class="visually-hidden">Loading...</span>--}}
{{--                        </div>--}}
{{--                    </button>--}}

{{--                    <button type="button" class="btn btn-gradient-primary btn-sm w-100 export-excel-btn"--}}
{{--                            onclick="startExportExcel()">--}}
{{--                        <span class="me-2">📥</span>--}}
{{--                        <span class="export-excel-text">Download Excel</span>--}}
{{--                        <div class="spinner-excel-border spinner-border-sm d-none" role="status">--}}
{{--                            <span class="visually-hidden">Loading...</span>--}}
{{--                        </div>--}}
{{--                    </button>--}}
{{--                    <div class="export-bg-pattern"></div>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="col-md-6 mb-3">--}}
{{--                <div class="export-card p-4 rounded border h-100 position-relative overflow-hidden">--}}
{{--                    <div class="d-flex align-items-start mb-3">--}}
{{--                        <div class="icon-sm bg-gradient-primary rounded me-3">--}}
{{--                            <span class="icon-symbol-sm">📊</span>--}}
{{--                        </div>--}}
{{--                        <div class="flex-grow-1">--}}
{{--                            <h6 class="mb-1 font-weight-bold">User Profile Data</h6>--}}
{{--                            <p class="text-muted small mb-0">Export user profile data.--}}
{{--                            </p>--}}
{{--                        </div>--}}
{{--                    </div>--}}

{{--                    <button type="button" class="btn btn-gradient-primary btn-sm w-100 export-profile-btn mb-3"--}}
{{--                            onclick="startExportProfile()">--}}
{{--                        <span class="me-2">📥</span>--}}
{{--                        <span class="export-profile-text">Download CSV</span>--}}
{{--                        <div class="spinner-profile-border spinner-border-sm d-none" role="status">--}}
{{--                            <span class="visually-hidden">Loading...</span>--}}
{{--                        </div>--}}
{{--                    </button>--}}

{{--                    <button type="button" class="btn btn-gradient-primary btn-sm w-100 export-excel-profile-btn"--}}
{{--                            onclick="startExportExcelProfile()">--}}
{{--                        <span class="me-2">📥</span>--}}
{{--                        <span class="export-excel-profile-text">Download Excel</span>--}}
{{--                        <div class="spinner-excel-profile-border spinner-border-sm d-none" role="status">--}}
{{--                            <span class="visually-hidden">Loading...</span>--}}
{{--                        </div>--}}
{{--                    </button>--}}
{{--                    <div class="export-bg-pattern"></div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="card">--}}
{{--            <div class="card-header border-0 shadow-lg gradient-card-blue text-white p-3 ps-4">--}}
{{--                <div class="d-flex justify-content-between align-items-center">--}}
{{--                    <h4 class="mb-2 mb-md-0 font-weight-bold">CHART</h4>--}}

{{--                    <div class="btn-group">--}}
{{--                        <a href="{{ route('dashboard', ['range'=>'week']) }}" class="btn btn-sm {{ $range=='week'?'btn-light text-primary':'btn-outline-light text-white' }}">7 days</a>--}}
{{--                        <a href="{{ route('dashboard', ['range'=>'month']) }}" class="btn btn-sm {{ $range=='month'?'btn-light text-primary':'btn-outline-light text-white' }}">30 days</a>--}}
{{--                        <a href="{{ route('dashboard', ['range'=>'all']) }}" class="btn btn-sm {{ $range=='all'?'btn-light text-primary':'btn-outline-light text-white' }}">All</a>--}}
{{--                    </div>--}}

{{--                    <form>--}}
{{--                        <div class="form-inline">--}}
{{--                            <div class="input-group d-flex align-items-center">--}}
{{--                                <input type="text" class="me-2 form-control bg-white datepicker" name="from_date" value="{{ $fromDate }}"--}}
{{--                                       placeholder="Ngày bắt đầu">--}}
{{--                                <input type="text" class="me-2 form-control bg-white datepicker" name="to_date" value="{{ $toDate }}"--}}
{{--                                       placeholder="Ngày kết thúc">--}}
{{--                                <div class="input-group-append ms-2">--}}
{{--                                    <button class="btn btn-sm btn-primary" type="submit">Tìm</button>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--            <div class="card-body p-4 position-relative overflow-hidden" style="height:300px;">--}}
{{--                <canvas id="myChart"></canvas>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
@endsection
