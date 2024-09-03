@extends('layouts.master')

@section('title', 'My Precense')

@section('content')
    <x-navbar title_page="My Precense">
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-body">
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
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">STAFF</p>
                                            <p class="text-xs text-secondary mb-0">{{ $precense?->employe?->position }}</p>
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
    </x-navbar>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            var table = $('#table-precense').DataTable({
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
        });
    </script>
@endpush