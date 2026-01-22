@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3 d-flex justify-content-between align-items-center">
                    <h2 style="margin: 0 !important;">Lượt chơi</h2>
                    <a href="{{ route('gaming-session.index') }}" class="btn btn-secondary">
                        ←  Quay về
                    </a>
                </div>
            </div>
            {{-- ẢNH GỐC (UPLOAD BAN ĐẦU) --}}
            @if($uploadUrl)
                <div class="card mb-4">
                    <div class="card-body row d-flex justify-content-evenly">
                        <div class="col-md-3">
                            <div class="card fade-in">
                                <a target="_blank" href="{{ $uploadUrl }}">
                                    <img src="{{ $uploadUrl }}" alt="Ảnh upload"
                                         class="card-img-top"
                                         style="object-fit: cover; height: 300px;">
                                </a>
                                <div class="card-body text-center d-flex align-items-center justify-content-center">
                                    <h5 class="mb-0">Ảnh gốc người chơi đã upload</h5>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card fade-in">
                                <a target="_blank" href="{{ $hasFrameUrl }}">
                                    <img src="{{ $hasFrameUrl }}" alt="Ảnh có khung"
                                         class="card-img-top"
                                         style="object-fit: cover; height: 300px;">
                                </a>
                                <div class="card-body text-center d-flex align-items-center justify-content-center">
                                    <h5 class="mb-0">Ảnh có khung</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- 4 ẢNH OUTCOME --}}
            <div class="card mb-4">
                <div class="card-body">
                    <h5>Kết quả hệ thống tạo</h5>
                    <div class="row mb-4">
                        @foreach($images as $key => $imageUrl)
                            @if($imageUrl)
                                <div class="col-md-6 mb-4">
                                    <div class="card {{ $playerChooseImage === $key ? 'border-success shadow' : '' }}">
                                        <a target="_blank" href="{{ $imageUrl }}">
                                            <img src="{{ $imageUrl }}" alt="{{ $key }}" class="card-img-top" style="object-fit: cover; height: 600px;">
                                        </a>
                                        <div class="card-body text-center d-flex align-items-center justify-content-center">
                                            <strong>{{ strtoupper(str_replace('_', ' ', $key)) }}</strong>
                                            @if($playerChooseImage === $key)
                                                <span class="badge bg-success ms-2">Đã chọn</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
