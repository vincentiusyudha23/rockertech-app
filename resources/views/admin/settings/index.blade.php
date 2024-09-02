@extends('layouts.master')

@section('title', 'Settings')

@push('styles')
    <style>
        .card-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 10px;
        }

        .card-item:hover {
            cursor: pointer;
            background: rgb(0, 0, 0, 0.1);
            scale: 0.95;
            transition: 0.1s ease;
        }

        .card-item .icon-item {
            width: 40px;
            height: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            color: white;
        }
    </style>
@endpush

@section('content')
    <x-navbar title_page="Settings">
        <div class="py-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        @foreach ($settings as $setting)
                            <div class="col-12 col-sm-6 col-lg-4 p-2 mb-3">
                                <a href="javascript:void(0)" class="card-item border rounded-3 w-100" data-bs-toggle="modal"
                                    data-bs-target="#modal-{{ $loop->index + 1 }}">
                                    <div class="icon-item bg-gradient-info shadow">
                                        <i class="{{ $setting['icon'] }} fa-lg"></i>
                                    </div>
                                    <span class="fs-5 fw-bold">{{ $setting['title'] }}</span>
                                </a>
                            </div>
                            <div class="modal fade" id="modal-{{ $loop->index + 1 }}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="d-flex align-items-center gap-2">
                                                <h6 class="modal-title font-weight-bold" id="exampleModalLabel">
                                                    {{ $setting['title'] }}
                                                </h6>
                                                <span>
                                                    <i class="{{ $setting['icon'] }}"></i>
                                                </span>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form id="{{ Str::slug($setting['title']) }}" data-key="{{ $setting['key'] ?? '' }}" action="{{ $setting['route'] }}" method="{{ $setting['methode'] }}">
                                                @foreach ($setting['field'] ?? [] as $item)
                                                    @if ($item['type'] == 'text')
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="basic-url">{{ $item['title'] }}</label>
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" {{ $item['option'] }} value="{{ $item['value'] ?? '' }}" aria-describedby="basic-addon3">
                                                            </div>
                                                        </div>
                                                        @if ($setting['key'] === 'api-token')
                                                            <a href="{{ route('admin.make_api_token') }}" class="btn bg-gradient-info w-100" type="button">Make Token</a>
                                                        @endif
                                                    @endif
                                                    @if ($item['type'] == 'textarea')
                                                        <div class="form-group">
                                                            <label class="form-control-label">{{ $item['title'] }}</label>
                                                            <div class="input-group">
                                                                <textarea class="form-control" name="{{ $item['name'] ?? '' }}" aria-label="With textarea">{{ $item['value'] ?? '' }}</textarea>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    @if ($item['type'] == 'time')
                                                        <div class="form-group">
                                                            <label class="form-control-label"
                                                                for="basic-url">{{ $item['title'] }}</label>
                                                            <div class="input-group">
                                                                <input type="time" name="{{ $item['name'] ?? '' }}" class="form-control" {{ $item['option'] ?? '' }} value="{{ $item['value'] ?? '' }}" aria-describedby="basic-addon3">
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach

                                                @if ($setting['button'])
                                                    <div class="w-100 mt-4">
                                                        <button class="btn w-100 bg-gradient-info fs-6" type="submit">
                                                            Save
                                                        </button>
                                                    </div>
                                                @endif
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </x-navbar>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('button[type="submit"]').on('click', function(e){
                e.preventDefault();
                var el = $(this);
                var form = el.closest('form');
                var formData = new FormData(form[0]);
                var url = form.attr('action');
                
                $.ajax({
                    type: 'post',
                    url: url,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: formData,
                    beforeSend: function(){
                        el.html('<i class="fa-solid fa-spinner fa-spin"></i>');
                        el.addClass('disabled');
                    },
                    success: function(response){
                        if(response.type == 'success'){
                            toastr.success(response.msg);
                        }
                    },
                    complete: function(){
                        el.text('Save');
                        el.removeClass('disabled');
                    }
                });
                
            });
        });
    </script>
@endpush
