@extends('layouts.master')

@section('title', 'My Precense')

@section('content')
    <x-navbar title_page="My Precense">
        <div class="container-fluid py-4">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="table-precense">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Position</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Time</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
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
                                                    <img src="{{ $image['img_url'] ?? '' }}" class="avatar avatar-md avatar-scale-up me-3" alt="{{ $image['alt'] ?? '' }}">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $precense?->employe?->name }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $precense?->employe?->position }}</p>
                                            <p class="text-xs text-secondary mb-0">Programmer</p>
                                        </td>
                                        <td class="align-middle px-3">
                                            {!! labelType($precense->type) !!}
                                        </td>
                                        <td class="px-3">{!! labelStatus($precense->status) !!}</td>
                                        <td class="text-center text-bold">{{ Carbon\Carbon::parse($precense->time)->format('H:i') }}</td>
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
    </x-navbar>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            $('#table-precense').DataTable();
        });
    </script>
@endpush