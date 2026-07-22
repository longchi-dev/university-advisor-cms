@extends('layouts.cms')
@section('content')
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="h3 mb-0 text-dark font-weight-bold animate-fade-in">Bảng điều khiển hệ thống</h1>
                        <p class="text-muted mb-0 animate-slide-up">
                            Theo dõi tình trạng hoạt động của hệ thống tư vấn tuyển sinh và dữ liệu tuyển sinh.
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
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 card-hover gradient-card-blue">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1 opacity-90">
                                    Số người dùng
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-white animate-count-up">
                                    {{ number_format($totalUsers) }}
                                </div>
                                <div class="text-xs text-white mt-1 opacity-80">
                                    <span class="status-indicator info"></span> Tổng số người dùng đã đăng ký
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

            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 card-hover gradient-card-green">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1 opacity-90">
                                    Phiên tư vấn
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-white animate-count-up">
                                    {{ number_format($totalChatSessions) }}
                                </div>
                                <div class="text-xs text-white mt-1 opacity-80">
                                    <span class="status-indicator info"></span> Tổng số cuộc hội thoại
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-white bg-opacity-20">
                                    <span class="icon-symbol">💬</span>
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

            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 card-hover gradient-card-purple">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1 opacity-90">
                                    Tin nhắn
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-white animate-count-up">
                                    {{ number_format($totalMessages) }}
                                </div>
                                <div class="text-xs text-white mt-1 opacity-80">
                                    <span class="status-indicator info"></span> Tổng số tin nhắn
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-white bg-opacity-20">
                                    <span class="icon-symbol">💬</span>
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

            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 card-hover gradient-card-orange">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1 opacity-90">
                                    Tài liệu
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-white animate-count-up">
                                    {{ number_format($totalDocuments) }}
                                </div>
                                <div class="text-xs text-white mt-1 opacity-80">
                                    <span class="status-indicator info"></span> Tổng số tài liệu trong kho tri thức
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-white bg-opacity-20">
                                    <span class="icon-symbol">📄</span>
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

            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 card-hover gradient-card-cyan">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1 opacity-90">
                                    Trường đại học
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-white animate-count-up">
                                    {{ number_format($totalSchools) }}
                                </div>
                                <div class="text-xs text-white mt-1 opacity-80">
                                    <span class="status-indicator info"></span> Tổng số trường
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-white bg-opacity-20">
                                    <span class="icon-symbol">🏫</span>
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

            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 card-hover gradient-card-emerald">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1 opacity-90">
                                    Ngành đào tạo
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-white animate-count-up">
                                    {{ number_format($totalMajors) }}
                                </div>
                                <div class="text-xs text-white mt-1 opacity-80">
                                    <span class="status-indicator info"></span> Tổng số ngành
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-white bg-opacity-20">
                                    <span class="icon-symbol">🎓</span>
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
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 card-hover gradient-card-yellow">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1 opacity-90">
                                    Điểm chuẩn
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-white animate-count-up">
                                    {{ number_format($totalScores) }}
                                </div>
                                <div class="text-xs text-white mt-1 opacity-80">
                                    <span class="status-indicator info"></span> Tổng dữ liệu điểm chuẩn
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-white bg-opacity-20">
                                    <span class="icon-symbol">📊</span>
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
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-lg h-100 card-hover gradient-card-red">
                    <div class="card-body p-4 position-relative overflow-hidden">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1 opacity-90">
                                    Câu hỏi hôm nay
                                </div>
                                <div class="h4 mb-0 font-weight-bold text-white animate-count-up">
                                    {{ number_format($todayQuestions) }}
                                </div>
                                <div class="text-xs text-white mt-1 opacity-80">
                                    <span class="status-indicator info"></span> Số câu hỏi trong ngày
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="icon-circle bg-white bg-opacity-20">
                                    <span class="icon-symbol">❓</span>
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

        <!-- AI Performance -->
        <div class="row mb-4">
            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted text-uppercase small fw-bold">
                                    Thời gian phản hồi trung bình
                                </div>

                                <h3 class="fw-bold text-primary mt-1 mb-1">
                                    {{ number_format($avgExecutionTime, 2) }} ms
                                </h3>

                                <small class="text-muted">
                                    ⚡ Trung bình mỗi lần gọi AI
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted text-uppercase small fw-bold">
                                    Mô hình sử dụng nhiều nhất
                                </div>

                                <h3 class="fw-bold text-success mt-1 mb-1">
                                    {{ $topModel?->model }}
                                </h3>

                                <small class="text-muted">
                                    🤖 Mô hình AI phổ biến
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted text-uppercase small fw-bold">
                                    Token trung bình
                                </div>

                                <h3 class="fw-bold text-warning mt-1 mb-1">
                                    {{ number_format($avgTokens) }}
                                </h3>

                                <small class="text-muted">
                                    🔤 Token mỗi prompt
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-6 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted text-uppercase small fw-bold">
                                    Tỷ lệ thành công
                                </div>

                                <h3 class="fw-bold text-danger mt-1 mb-1">
                                    {{ number_format($successRate, 1) }}%
                                </h3>

                                <small class="text-muted">
                                    ✅ Prompt thực thi thành công
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================== BIỂU ĐỒ ================== -->
        <div class="row">
            {{-- Top trường --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            🏫 Top trường được quan tâm
                        </h5>
                    </div>

                    <div class="card-body">
                        <canvas id="schoolChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            {{-- Top ngành --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            🎓 Top ngành được quan tâm
                        </h5>
                    </div>

                    <div class="card-body">
                        <canvas id="majorChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Câu hỏi 7 ngày --}}
            <div class="col-lg-12 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            📈 Số lượng câu hỏi trong 7 ngày gần đây
                        </h5>
                    </div>

                    <div class="card-body">
                        <canvas id="questionChart" height="120"></canvas>
                    </div>
                </div>
            </div>


        </div>

        <div class="row">
            {{-- Điểm chuẩn theo năm --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            📅 Dữ liệu điểm chuẩn theo năm
                        </h5>
                    </div>

                    <div class="card-body">
                        <div style="height:300px">
                            <canvas id="scoreYearChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            📊 Phân bố ý định người dùng
                        </h5>
                    </div>

                    <div class="card-body">
                        <div style="height:300px">
                            <canvas id="intentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Prompt Type --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            🤖 Tần suất sử dụng Prompt
                        </h5>
                    </div>

                    <div class="card-body">
                        <canvas id="promptTypeChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Success/Error --}}
            <div class="col-lg-6 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">
                            ✅ Tỷ lệ thực thi Prompt
                        </h5>
                    </div>

                    <div class="card-body">
                        <canvas id="successChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================== TOP QUESTION ================== --}}

        <div class="card shadow-sm border-0 mb-4">

            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    🔥 Những câu hỏi được hỏi nhiều nhất
                </h5>
            </div>

            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                    <tr>
                        <th width="80">#</th>
                        <th>Câu hỏi</th>
                        <th width="150" class="text-end">
                            Lượt hỏi
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($topQuestions as $index => $question)
                            <tr>
                                <td>
                                    {{ $index + 1 }}
                                </td>
                                <td>
                                    {{ $question['question'] }}
                                </td>

                                <td class="text-end fw-bold">
                                    {{ $question['total'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ================== PROMPT TB ================== --}}

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">
                    🐢 Thời gian xử lý trung bình của từng loại Prompt
                </h5>
            </div>

            <div class="card-body">
                <table class="table table-hover align-middle">
                    <thead>
                    <tr>
                        <th>Prompt</th>
                        <th width="180">
                            Trung bình (ms)
                        </th>
                        <th width="180">
                            Lâu nhất (ms)
                        </th>
                        <th width="120">
                            Số lượt
                        </th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($promptPerformance as $prompt)
                        <tr>
                            <td>
                                {{ $prompt->prompt_type->value ?? $prompt->prompt_type }}
                            </td>
                            <td>
                                {{ number_format($prompt->avg_time, 2) }}
                            </td>
                            <td>
                                {{ number_format($prompt->max_time, 2) }}
                            </td>
                            <td>
                                {{ $prompt->total }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Chart.plugins.register(ChartDataLabels);
            const colors = [
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b',
                '#6f42c1',
                '#fd7e14',
                '#20c997',
                '#17a2b8',
                '#6610f2'
            ];

            // ==========================
            // Top trường
            // ==========================
            new Chart(document.getElementById('schoolChart'), {
                type: 'horizontalBar',
                data: {
                    labels: @json($topSchools->pluck('school')->values()),
                    datasets: [{
                        label: 'Lượt hỏi',
                        data: @json($topSchools->pluck('total')->values()),
                        backgroundColor: '#4e73df',
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                                fontColor: '#343a40', // màu số
                                fontSize: 12
                            },
                            gridLines: {
                                color: '#eeeeee'
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                fontColor: '#343a40', // tên trường
                                fontSize: 12
                            },
                            gridLines: {
                                display: false
                            }
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                        }
                    }
                }
            });


            // ==========================
            // Top ngành
            // ==========================
            const majorLabels = @json($topMajors->pluck('major')->values());

            new Chart(document.getElementById('majorChart'), {
                type: 'bar',
                data: {
                    labels: majorLabels,
                    datasets: [{
                        label: 'Lượt hỏi',
                        data: @json($topMajors->pluck('total')->values()),
                        backgroundColor: colors,
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        callbacks: {
                            title: function (tooltipItem) {
                                return majorLabels[tooltipItem[0].index];
                            }
                        }
                    },
                    scales: {
                        xAxes: [{
                            ticks: {
                                autoSkip: false,
                                maxRotation: 0,
                                minRotation: 0,
                                fontColor: '#6c757d',
                                callback: function (value) {
                                    if (value.length > 16) {
                                        return value.substring(0, 16) + '...';
                                    }
                                    return value;
                                }
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                                fontColor: '#6c757d'
                            }
                        }]
                    },
                    plugins: {
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                        }
                    }
                }
            });

            // ==========================
            // Question by day
            // ==========================
            new Chart(document.getElementById('questionChart'), {
                type: 'line',
                data: {
                    labels: @json($questionByDays->pluck('date')->values()),
                    datasets: [{
                        label: 'Số câu hỏi',
                        data: @json($questionByDays->pluck('total')->values()),
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78,115,223,.15)',
                        fill: true,
                        lineTension: .3
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: true
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            });

            new Chart(document.getElementById('scoreYearChart'), {
                type: 'pie',
                data: {
                    labels: @json($scoresByYear->pluck('year')->values()),
                    datasets: [{
                        data: @json($scoresByYear->pluck('total')->values()),
                        backgroundColor: colors
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom'
                    },
                    plugins: {
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 12
                            },
                            formatter: function(value, context) {
                                let data = context.dataset.data;
                                let total = data.reduce((a, b) => a + b, 0);
                                return Math.round(value / total * 100) + '%';
                            }
                        }
                    }
                }
            });

            const intentColors = [
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b'
            ];


            new Chart(document.getElementById('intentChart'), {
                type: 'doughnut',
                data: {
                    labels: @json(
                        $intentStats->pluck('intent')->values()
                    ),
                    datasets: [{
                        data: @json(
                            $intentStats->pluck('total')->values()
                        ),
                        backgroundColor: intentColors,
                        borderWidth: 2
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    legend: {
                        position: 'bottom'
                    },
                    plugins: {
                        datalabels: {
                            color: '#fff',
                            anchor: 'center',
                            align: 'center',
                            font: {
                                weight: 'bold',
                            },
                            formatter: function(value, context) {
                                let data = context.dataset.data;
                                let total = data.reduce(
                                    (a,b)=>a+b,
                                    0
                                );
                                return Math.round(value / total * 100) + '%';
                            }
                        }
                    }
                }
            });

            new Chart(document.getElementById('promptTypeChart'), {
                type: 'doughnut',
                data: {
                    labels: @json($promptTypes->pluck('prompt_type')->values()),
                    datasets: [{
                        data: @json($promptTypes->pluck('total')->values()),
                        backgroundColor: colors
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom'
                    },
                    plugins: {
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold'
                            },
                            formatter: function(value, context) {
                                let total = context.dataset.data.reduce((a,b)=>a+b,0);
                                return Math.round(value / total * 100) + '%';
                            }
                        }
                    }
                }
            });

            new Chart(document.getElementById('successChart'), {
                type: 'pie',
                data: {
                    labels: ['Success', 'Error'],
                    datasets: [{
                        data: [{{ $successRate }}, {{ $errorRate }}],
                        backgroundColor: [
                            '#1cc88a',
                            '#e74a3b'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom'
                    },
                    plugins: {
                        datalabels: {
                            color: '#fff',
                            font: {
                                weight: 'bold',
                                size: 14
                            },
                            formatter: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection
