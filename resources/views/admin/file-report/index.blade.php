@extends('layouts.master')

@section('title', 'File Report')

@section('content')
    <x-navbar title_page="File Report">
        <div class="px-2 py-2">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="table-employee">
                            <thead>
                                <tr>
                                    <th
                                        class="text-uppercase text-secondary text-center text-xxs font-weight-bolder opacity-7" style="width: 20px;">
                                        No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Path Name</th>
                                    <th class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Month</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Year</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Download</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($backup as $item)
                                    <tr>
                                        <td class="text-center align-center">{{ $loop->index + 1 }}</td>
                                        <td class="text-start align-center">{{ $item->path }}</td>
                                        <td class="text-center align-center">{{ $item->created_at->translatedFormat('F') }}</td>
                                        <td class="text-center align-center">{{ $item->created_at->format('Y') }}</td>
                                        <td class="text-center align-middle">
                                            <a href="{{ assets('backup/'.$item->path) }}" download class="bg-info px-2 py-1 rounded-2 text-white" style="cursor: pointer;">
                                                <i class="fa-solid fa-download fa-md"></i>
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
