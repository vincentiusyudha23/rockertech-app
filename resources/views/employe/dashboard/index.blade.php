@extends('layouts.master')

@section('title', 'Dashboard')


@section('content')
    <x-navbar title_page="Dashboard">
        <div class="container-fluid py-2">
            <div class="row">
                <div class="col-12 col-md-5 p-2">
                    @include('employe.dashboard.partials.left_side')
                </div>
                <div class="col-12 col-md-7 p-2 mt-2 mt-md-0">
                    @include('employe.dashboard.partials.right_side')
                </div>
            </div>
        </div>
    </x-navbar>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>
    <script>
        $(document).ready(function(){
            function initCam(){
                Webcam.set({
                    width: 450,
                    height: 315.5,
                    image_format: 'jpeg',
                    jpeg_quality: 90,
                });
                Webcam.attach('#camera-feed');
            }

            $('#photo_tab').click(function(){
                initCam();
            });

            $(document).on('click','#image_tab',function(){
                Webcam.reset();
            });

            $(document).on('click', '#take-photo', function(e){
                e.preventDefault();
                Webcam.snap(function(data_uri,err){
                    $('#camera-feed').html('<img src="'+data_uri+'"/>');
                });
            });

            $('#choose-btn').click(function(){
                $('#file-input').click();
            })

            $('#file-input').on('change', function() {
                var file = this.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#image-preview').attr('class', 'rounded-3').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(file);

                    var formData = new FormData();
                    formData.append('file', file);

                    $.ajax({
                        url: "{{ route('employe.media-upload') }}",
                        method: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            $('#image-save').val(response.id);
                            $('#save-btn').removeClass('d-none');
                            toastr.success('File uploaded successfully');
                        },
                        error: function(xhr) {
                            alert('File upload failed');
                        }
                    });
                }
            });

            $(document).on('click', '#save-btn', function(){
                var el = $(this);
                var image_id = $('#image-save').val();

                Swal.fire({
                    title: "Precense From Home",
                    text: "Do you want a precense?",
                    icon: 'question',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: "Yes",
                    denyButtonText: "Cancel"
                }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {

                    $.ajax({
                        type: 'POST',
                        url: '{{ route("employe.whf-precense") }}',
                        data: {
                            _token: '{{ csrf_token() }}',
                            image: image_id
                        },
                        beforeSend: function(){
                            Swal.fire({
                                title: 'Please Wait...',
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                willOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                        },
                        success: function(response){
                            Swal.hideLoading();
                            if(response.type == 'success'){
                                Swal.fire(response.msg, "", "success");
                            }

                            if($response.type == 'error'){
                                Swal.fire(response.msg, "", "error");
                            }
                        }
                    });
                } else if (result.isDenied) {
                    Swal.fire("Changes are not saved", "", "info");
                }
});
            });
        });    
    </script>
@endpush