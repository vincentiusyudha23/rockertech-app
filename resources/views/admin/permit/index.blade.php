@extends('layouts.master')

@section('title', 'Permit Submission List')

@section('content')
    <x-navbar title_page="Permit Submission List">
        <div class="p-2 w-100">
            @if (session('success'))
                <div class="alert alert-success" role="alert">
                    <strong>{{ session('success') }}</strong>
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <x-table-permit :permits="$permits"/>
                </div>
            </div>
        </div>
    </x-navbar>
@endsection