@extends('layouts.master')

@section('title', 'To-do List')

@section('content')
    <x-navbar title_page="To-do List">
        <div class="p-2 w-100">
            <div class="card">
                <div class="card-body">
                    <x-table-todolist :todolist="$todolist"/>
                </div>
            </div>
        </div>
    </x-navbar>
@endsection