@extends('layouts.master')

@section('title', 'Presence')

@section('content')
    <x-navbar title_page="Presence">
        <div class="py-3">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-end align-items-center">
                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#exampleModal">Export</button>
                    </div>
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="table-precense">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Position</th>
                                    <th class="text-uppercase text-secondary text-center text-xxs font-weight-bolder opacity-7 ps-2">
                                        Type</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-center text-xxs font-weight-bolder opacity-7">
                                        Status</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Time</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Date</th>
                                    {{-- <th class="text-secondary opacity-7"></th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($precenses as $precense)
                                    <tr>
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
                                            <p class="text-xs font-weight-bold mb-0">{{ $precense?->employe?->position }}
                                            </p>
                                            <p class="text-xs text-secondary mb-0">Programmer</p>
                                        </td>
                                        <td class="align-middle text-center">
                                            {!! labelType($precense->type) !!}
                                        </td>
                                        <td class="align-middle text-center">{!! labelStatus($precense->status) !!}</td>
                                        <td class="text-center text-bold">
                                            {{ Carbon\Carbon::parse($precense->time)->format('H:i') }}</td>
                                        <td class="text-center text-bold">{{ $precense->created_at->format('d-m-Y') }}</td>
                                        {{-- <td></td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
            $('#table-precense').DataTable({
                order: [
                    [5, 'desc']
                ]
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
        });
    </script>
@endpush
