@extends('layouts.master')

@section('title', 'Edit Employee')

@push('styles')
    <style>
        .page-header .btn-back {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
        }
    </style>
@endpush

@section('content')
    <x-navbar title_page="Edit Employee">
        <div class="container-fluid">
            <div class="page-header min-height-300 border-radius-xl mt-4"
                style="background-image: url('{{ assets('img/curved-images/curved0.jpg') }}');  background-position-y: 30%;">
                <span class="mask bg-gradient-info opacity-8"></span>
                <a href="{{ route('admin.employee') }}" class="btn btn-sm btn-danger btn-back">Back</a>
            </div>
            <div class="card card-body blur shadow-blur mx-4 mt-n6 overflow-hidden">
                <div class="row gx-4">
                    <div class="col-auto">
                        <div class="position-relative">
                            <img src="{{ get_data_image($employe->image)['img_url'] ?? '' }}" alt="profile_image"
                                class="w-100 avatar avatar-xl shadow-sm">
                        </div>
                    </div>
                    <div class="col-auto my-auto">
                        <div class="h-100">
                            <h5 class="mb-1">
                                {{ $employe->name }}
                            </h5>
                            <p class="mb-0 font-weight-bold text-sm text-uppercase">
                                {{ $employe->position }}
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1 active " data-bs-toggle="tab"
                                        data-bs-target="#profile" id="profile-tab" href="javascript:;" role="tab"
                                        aria-selected="true">
                                        Profile
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link mb-0 px-0 py-1" id="precense-tab" data-bs-toggle="tab"
                                        href="javascript:;" role="tab" aria-selected="true" data-bs-target="#precense">
                                        Precense
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="card">
                                <div class="card-header pb-0 p-3">
                                    <h6 class="mb-1">Employe Information</h6>
                                </div>
                                <div class="card-body p-3">
                                    @if (session('success'))
                                        <div class="alert alert-success text-white fw-bold" role="alert">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @if ($errors->any())
                                        <div class="alert alert-danger" role="alert">
                                            <ul class="p-0 m-0 ps-3">
                                                @foreach ($errors->all() as $msg)
                                                    <li class="text-white fw-bold">{{ $msg }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <form class="px-3" action="{{ route('admin.employee.update') }}" id="form-edit-employe" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $employe->id }}">
                                        <div class="row">
                                            <div class="col-12 col-lg-6 px-sm-5 pt-lg-5">
                                                <x-media-upload name="image" :value="$employe->image" />
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <div class="d-flex mb-3 justify-content-end align-items-end gap-2 w-100">
                                                    <div class="form-group p-0 m-0" style="width: 90%;">
                                                        <label class="form-control-label" for="basic-url">Card ID</label>
                                                        <div class="input-group">
                                                            <input type="text" id="card_id" class="form-control" value="{{ $employe->card_id }}" name="name" id="basic-url" aria-describedby="basic-addon3">
                                                        </div>
                                                    </div>
                                                    <a href="javascript:void(0)" style="width: 10%;" class="btn-remove-card bg-success py-1 text-center mb-1 text-white px-2 rounded-2" style="cursor: pointer;">
                                                        <i class="fa-solid fa-check fa-lg"></i>
                                                    </a>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label" for="basic-url">Name<sup class="text-danger">*</sup></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" required
                                                            value="{{ $employe->name }}" name="name" id="basic-url"
                                                            aria-describedby="basic-addon3">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label" for="basic-url">Position<sup class="text-danger">*</sup></label>
                                                    <div class="input-group">
                                                        <select class="form-select" name="position" required>
                                                            <option value="">Select Positon</option>
                                                            <option value="1" {{ $employe->position == 1 ? 'selected' : 0 }}>Content Planner</option>
                                                            <option value="2" {{ $employe->position == 2 ? 'selected' : 0 }}>Designer</option>
                                                            <option value="3" {{ $employe->position == 3 ? 'selected' : 0 }}>Business Admin</option>
                                                            <option value="4" {{ $employe->position == 4 ? 'selected' : 0 }}>Sales</option>
                                                            <option value="5" {{ $employe->position == 5 ? 'selected' : 0 }}>Assistant Manager</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="basic-url">Email<sup class="text-danger">*</sup></label>
                                                    <div class="input-group">
                                                        <input type="email" class="form-control" required
                                                            value="{{ $employe->email }}" name="email" id="basic-url"
                                                            aria-describedby="basic-addon3">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label" for="basic-url">Mobile Number<sup class="text-danger">*</sup></label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" required
                                                            value="{{ $employe->mobile }}" name="mobile" id="basic-url"
                                                            aria-describedby="basic-addon3">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="basic-url">NIK<sup class="text-danger">*</sup></label>
                                                    <div class="input-group">
                                                        <input type="number" class="form-control" required
                                                            value="{{ $employe->nik }}" name="nik" id="basic-url"
                                                            aria-describedby="basic-addon3">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label" for="basic-url">Date of Birth<sup class="text-danger">*</sup></label>
                                                    <div class="input-group">
                                                        <input type="date" class="form-control" required name="date_birth"
                                                            value="{{ $employe->birthday->format('Y-m-d') }}"
                                                            id="basic-url" aria-describedby="basic-addon3">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group mb-4">
                                                    <label for="address">Address<small class="text-xxs text-secondary opacity-8 font-italic"> (Optional)</small></label>
                                                    <input type="text" class="form-control" id="address"
                                                        name="address" value="{{ $employe?->address?->street_address }}"
                                                        placeholder="Street Address">
                                                    <div class="row mt-3">
                                                        <div class="col-12 col-md-6">
                                                            <input class="form-control" type="text" name="kelurahan"
                                                                value="{{ $employe?->address?->kelurahan }}"
                                                                placeholder="Kelurahan">
                                                            <input class="form-control mt-3" type="text"
                                                                name="kecamatan"
                                                                value="{{ $employe?->address?->kecamatan }}"
                                                                placeholder="Kecamatan">
                                                        </div>
                                                        <div class="col-12 col-md-6 mt-3 mt-md-0">
                                                            <input class="form-control" type="text" name="kota"
                                                                placeholder="Kota"
                                                                value="{{ $employe?->address?->kota }}">
                                                            <input class="form-control mt-3" type="text"
                                                                name="provinsi"
                                                                value="{{ $employe?->address?->provinsi }}"
                                                                placeholder="Provinsi">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="w-100 d-flex justify-content-end align-items-center">
                                            <button type="submit" class="btn bg-gradient-dark">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="precense" role="tabpanel" aria-labelledby="precense-tab">
                            <div class="card">
                                <div class="card-header pb-0 p-3">
                                    <h6 class="mb-1">Precense History</h6>
                                </div>
                                <div class="card-body p-3">
                                    <div class="table-responsive p-0">
                                        <table class="table align-items-center mb-0" id="table-precense">
                                            <thead>
                                                <tr>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Position</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($employe?->precense))
                                                    @foreach ($employe?->precense as $precense)
                                                        <tr>
                                                            <td class="text-bold text-center">
                                                                <p class="text-sm font-weight-bold mb-0">
                                                                    {{ $precense->created_at->format('d-m-Y') }}
                                                                </p>
                                                                <p class="text-sm text-secondary mb-0">
                                                                    {{ Carbon\Carbon::parse($precense->time)->format('H:i') }}
                                                                </p>
                                                            </td>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div>
                                                                        @php
                                                                            $image = get_data_image($precense?->employe?->image);
                                                                        @endphp
                                                                        <img src="{{ $image['img_url'] ?? '' }}"
                                                                            class="avatar avatar-md avatar-scale-up me-3"
                                                                            alt="{{ $image['alt'] ?? '' }}">
                                                                    </div>
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">{{ $precense?->employe?->name }}</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs font-weight-bold mb-0">STAFF</p>
                                                                <p class="text-xs text-secondary mb-0">{{ labelPosition($precense?->employe?->position) }}</p>
                                                            </td>
                                                            <td class="align-middle text-center">
                                                                {!! labelType($precense->type) !!}
                                                            </td>
                                                            <td class="align-middle text-center">{!! labelStatus($precense->status) !!}</td>
                                                            <td class="text-center text-bold">
                                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#open-img-modal" class="open-image" data-src="{{ get_data_image($precense->image)['img_url'] }}">
                                                                    <img class="avatar avatar-xl avatar-scale-up" src="{{ get_data_image($precense->image)['img_url'] }}" alt="image">
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {

            function declarTable(status){
                if(status){
                    $('#table-precense').DataTable({
                        scrollX: true,
                        responsive: false
                    });
                }else{
                    $('#table-precense').DataTable().destroy();
                }
            };

            // declarTable();

            $(document).on('click', '#precense-tab', function(){
                declarTable(true);
            });

            $(document).on('click', '#profile-tab', function(){
                declarTable(false);
            });
            $('#form-edit-employe').on('submit', function(){
                $('button[type="submit"]').html('<i class="fa-solid fa-spinner fa-spin fa-xl px-4"></i>').addClass('disabled');
            });

            $('.btn-remove-card').on('click', function() {
                var el = $(this);
                var card_id = el.parent().find('#card_id').val();

                $.ajax({
                    type: 'post',
                    url: '{{ route('admin.employee.edit.card') }}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        employee_id: "{{ $employe->id }}",
                        card_id: card_id
                    },
                    beforeSend: function() {
                        el.html('<i class="fa-solid fa-spinner fa-spin fa-lg"></i>').addClass(
                            'disabled');
                    },
                    success: function(response) {
                        toastr.success(response.msg);
                        el.html('<i class="fa-solid fa-check fa-lg"></i>').removeClass(
                            'disabled');
                    }
                });
            })
        });
    </script>
@endpush
