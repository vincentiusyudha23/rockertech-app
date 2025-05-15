@extends('layouts.master')

@section('title', 'Presence')

@section('content')
    <x-navbar title_page="Presence">
        <div class="py-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="width: 130px;">
                            <select id="filter-month" class="form-select form-select-sm px-3" aria-label="Default select example">
                                <option value="all" selected>All Month</option>
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div>
                            <button type="button" class="btn btn-info btn-sm m-0" data-bs-toggle="modal" data-bs-target="#exampleModal">Export</button>
                        </div>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="table-precense">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">Position</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($precenses as $precense)
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
                                        <td class="text-center">
                                            <p class="text-xs text-secondary font-weight-bold mb-0">{{  labelPosition($precense?->employe?->position) }}</p>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="open-img-modal" tabindex="-1" role="dialog" aria-labelledby="label-open-image" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body" id="body-image-modal">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Export Precense</h5>
                        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('admin.export') }}">
                            @csrf
                            <div class="form-group">
                                <label class="form-control-label" for="employe">Employe</label>
                                <div class="input-group">
                                    <select class="form-control select2" id="employe" style="width: 100%;" name="employe[]" multiple>
                                        <option value="all">All</option>
                                        @foreach ($employes as $employe)
                                            <option value="{{ $employe?->id }}">{{ $employe?->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-control-label">From Date</label>
                                        <div class="input-group">
                                            <input type="text" id="datePick" placeholder="Date" class="form-control datepicker" name="from_date">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-control-label">End Date</label>
                                        <div class="input-group">
                                            <input type="text" id="datePick" placeholder="Date" class="form-control datepicker" name="end_date">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 w-100">
                                <button type="submit" class="btn bg-gradient-info w-100">Download</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            var table = $('#table-precense').DataTable({
                order: [
                    [5, 'desc']
                ],
                scrollX: true,
                responsive: false
            });

            $('#filter-month').change(function(){
                var selectedMonth = $(this).val();
                if (selectedMonth === "all") {
                    table.column(0).search('').draw();  // Reset pencarian untuk menampilkan semua data
                } else {
                    table.column(0).search('-' + selectedMonth + '-', true, false).draw();
                }
            });


            $('.select2').select2({
                dropdownParent: $('#exampleModal'),
                theme: "classic",
                placeholder: "Select Employe"
            });

            if (document.querySelector('.datepicker')) {
                flatpickr('.datepicker', {
                    mode: "single",
                    dateFormat: "d-m-Y"
                });
            }

            $(document).on('click' , '.open-image', function(){
                var el = $(this);
                var src = el.data('src');
                $('#body-image-modal').html('<img src="'+src+'" class="w-100 h-100">')
            })
        });
    </script>
@endpush
