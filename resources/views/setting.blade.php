@extends('layouts.cms')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="position-relative">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="m-0">Cài đặt</h2>
                    </div>
                </div>
            </div>

            <div>
                <form action="{{ route('setting.update') }}" method="POST">
                    @method('PATCH')
                    @csrf
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Prompt image (<span style="color: red">{theme}</span> là chỗ để theme, KHÔNG ĐƯỢC ĐỔI)</h5>
                            <div class="row mb-4">
                                <div class="form-group">
                                    <textarea name="image" class="form-control form-control-lg" rows="15">{{ $promptImage }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <h5>Prompt validate</h5>
                            <div class="row mb-4">
                                <div class="form-group">
                                    <textarea name="validate" class="form-control form-control-lg" rows="5">{{ $promptValidate }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center mt-4">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="mdi mdi-content-save"></i>
                            <span class="ms-1">Lưu</span>
                        </button>
                    </div>
                </form>
            </div>

{{--            <form action="{{ route('setting.update') }}" method="POST">--}}
{{--                @method('PATCH')--}}
{{--                @csrf--}}
{{--                <div class="card mb-4">--}}
{{--                    @foreach($labels->chunk(10) as $chunk)--}}
{{--                        <div class="card-body row">--}}
{{--                            @foreach($chunk as $label)--}}
{{--                                 Label VN --}}
{{--                                <div class="col-md-6 mb-3">--}}
{{--                                    <label for="label_{{ $label->id }}" class="form-label">--}}
{{--                                        <strong>VN #{{ $label->id }}</strong>--}}
{{--                                    </label>--}}
{{--                                    <input type="text"--}}
{{--                                           name="labels[{{ $label->id }}][label]"--}}
{{--                                           id="label_{{ $label->id }}"--}}
{{--                                           class="form-control"--}}
{{--                                           value="{{ old("labels.{$label->id}.label", $label->label) }}">--}}
{{--                                </div>--}}

{{--                                 Label EN --}}
{{--                                <div class="col-md-6 mb-3">--}}
{{--                                    <label for="label_en_{{ $label->id }}" class="form-label">--}}
{{--                                        <strong>EN #{{ $label->id }}</strong>--}}
{{--                                    </label>--}}
{{--                                    <input type="text"--}}
{{--                                           name="labels[{{ $label->id }}][label_en]"--}}
{{--                                           id="label_en_{{ $label->id }}"--}}
{{--                                           class="form-control"--}}
{{--                                           value="{{ old("labels.{$label->id}.label_en", $label->label_en) }}">--}}
{{--                                </div>--}}

{{--                                @foreach($label->themes as $theme)--}}
{{--                                     Theme 1 --}}
{{--                                    <div class="col-md-6 mb-3">--}}
{{--                                        <label for="theme1_{{ $label->id }}" class="form-label">Theme 1 #{{ $label->id }}</label>--}}
{{--                                        <input type="text"--}}
{{--                                               name="labels[{{ $label->id }}][themes][{{$theme->id}}]"--}}
{{--                                               id="theme1_{{ $label->id }}"--}}
{{--                                               class="form-control"--}}
{{--                                               value="{{ old("labels.{$label->id}.themes.1", $theme->content ?? '') }}">--}}
{{--                                    </div>--}}
{{--                                @endforeach--}}
{{--                            @endforeach--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
{{--                </div>--}}


{{--                <div class="card mb-4">--}}
{{--                    <div class="card-body">--}}
{{--                        <h5>Prompt image (<span style="color: red">{theme}</span> là chỗ để theme, KHÔNG ĐƯỢC ĐỔI)</h5>--}}
{{--                        <div class="row mb-4">--}}
{{--                            <div class="form-group">--}}
{{--                                <textarea name="image" class="form-control form-control-lg" rows="15">{{ $promptImage }}</textarea>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="card mb-4">--}}
{{--                    <div class="card-body">--}}
{{--                        <h5>Prompt validate</h5>--}}
{{--                        <div class="row mb-4">--}}
{{--                            <div class="form-group">--}}
{{--                                <textarea name="validate" class="form-control form-control-lg" rows="5">{{ $promptValidate }}</textarea>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="d-flex justify-content-center mt-4">--}}
{{--                    <button type="submit" class="btn btn-primary px-4">--}}
{{--                        <i class="mdi mdi-content-save"></i>--}}
{{--                        <span class="ms-1">Lưu</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--            </form>--}}
        </div>
    </div>
@endsection
