@extends('layouts.master')

@section('title', 'Work From Home')

@section('content')
    <x-navbar title_page="Work From Home">
        <div class="container-fluid d-flex justify-content-center align-items-center overflow-hidden" style="height: 88vh;">
            <div class="card">
                <div class="card-body">
                    <x-media-upload name="image"></x-media-upload>
                </div>
            </div>
        </div>
    </x-navbar>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script>
        $(document).ready(function(){
            // function initCam(){
            //     Webcam.set({
            //         width: 240,
            //         height: 280,
            //         image_format: 'jpeg',
            //         jpeg_quality: 90
            //     });
            //     Webcam.attach('#camera-feed');
            // }

            // initCam();

        });    
    </script>
@endpush