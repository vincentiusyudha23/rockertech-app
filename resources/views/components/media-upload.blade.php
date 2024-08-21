@props(['name', 'value'])

<style>
    .dropzone {
        position: relative;
        z-index: 2;
        border: 2px dashed var(--bs-gray-500);
    }

    .dropzone p {
        pointer-events: none;
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 3;
    }

    .dropzone .dz-preview.dz-image-preview {
        width: 100%;
        background: var(--bs-gray-100);
        margin: 0;
    }

    .dz-preview.dz-image-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 9px;
    }

    .dropzone .dz-preview:hover .dz-image img {
        filter: blur(0)
    }

    .dz-preview .dz-back {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 24px;
        margin: 0;
        background: none;
    }

    .dropzone .dz-preview .dz-details {
        display: flex;
        flex-direction: column-reverse;
        justify-content: start;
        align-items: start;
        height: 100%;
        border-radius: 0.75rem;
        padding: 16px;
        opacity: 1;
        transition: .3s;
        z-index: 12;
    }

    .dropzone .dz-preview.dz-complete .dz-details {
        background: linear-gradient(180deg, rgb(38 215 119 / 15%), transparent) !important;
    }

    .dropzone .dz-preview.dz-error .dz-details {
        background: linear-gradient(180deg, rgb(255 57 57 / 15%), transparent) !important;
    }

    .dz-preview {
        width: 100%;
        display: flex;
        flex-direction: column;
    }

    .dz-filename {
        max-width: 300px;
    }

    @media(max-width:767px) {
        .dz-filename {
            max-width: 100%;
        }
    }

    .dz-filename span {
        display: none;
    }

    .dz-size span {
        display: none;
    }

    .dz-size strong {
        font-weight: 400;
    }

    .dropzone .dz-preview .dz-remove {
        z-index: 99;
        box-shadow: none;
    }

    .dropzone .dz-preview .dz-success-mark,
    .dropzone .dz-preview .dz-error-mark {
        display: flex;
        align-items: center;
        gap: 8px;
        left: auto;
        right: 16px;
        top: 16px;
        margin: 0 !important;
        background: #bb2525;
        color: #fff;
        padding: 6px 10px;
        line-height: 1;
        font-size: 12px;
        font-weight: 500;
        border-radius: 8px;
    }

    .dropzone .dz-preview .dz-success-mark {
        background: var(--bs-success);
    }

    [dir=rtl] .dropzone .dz-preview .dz-success-mark,
    [dir=rtl] .dropzone .dz-preview .dz-error-mark {
        right: auto;
        left: 16px;
    }

    .dropzone .dz-success-mark svg,
    .dropzone .dz-error-mark svg {
        max-width: 14px;
        max-height: 14px;
    }

    .dz-success-mark:before {
        content: '{{ __('Success') }}'
    }

    .dz-error-mark:before {
        content: '{{ __('Error') }}';
    }

    .dropzone .dz-preview.dz-error .dz-error-message {
        left: 50%;
        transform: translateX(-50%);
    }

    .dz-message>.dzImage {
        height: 125px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
    .dzImage .image-preview-data{
        max-width: 150px;
        max-height: 150px;
        object-fit: contain;
        position: absolute;
        border-radius: 10px;
    }
</style>
@php
    $ranId = str_replace(['0','1','2','3','4','5','6','7','8','9'], 'X', Str::random(8));;
@endphp
<div class="m-1">
    <div class="row" _id="{{ $ranId }}">
        <div class="dropzone" id="{{ $ranId }}">
            {{-- <p class="text-gray-700 text-center">
                <i class="fa-solid fa-cloud-arrow-up text-secondary px-2"></i>
                <span>
                    {{ __('Upload Your Image')}}
                </span>
            </p> --}}
            <div class="dz-message needsclick m-0">
                <div class="dzImage rounded bg-gray-200">
                    @if (isset($value))
                        <img src="{{ get_data_image($value)['img_url'] ?? '' }}" alt="img" class="avatar avatar-xxl">
                    @else
                        <i class="fa-solid fa-cloud-arrow-up text-secondary px-2"></i>
                        <span>Upload Your Image</span>
                    @endif
                </div>
            </div>
        </div>
        <input type="hidden" name="{{ $name }}" value="{{ $value ?? $id ?? '' }}">
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            var {{ $ranId }} = [];
            var type = 'image';
            var acceptedFiles = "image/*";

            var myDropzone_{{ $ranId }} = new Dropzone("#{{ $ranId }}", {
                url: '{{ route('admin.media-upload') }}',
                paramName: "file",
                maxFiles: 1,
                uploadMultiple: false,
                paralelUploads: 1,
                maxFilesize: 20,
                addRemoveLinks: true,
                acceptedFiles: acceptedFiles,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                sending: function(file, xhr, formData) {
                    $('div[_id={{ $ranId }}] ._done').hide();
                },
                init: function() {
                    this.on("removedfile", function(file) {
                        myDropzone_{{ $ranId }}.enable();
                        var id = parseInt(file._removeLink.getAttribute("data-file_id"));
                        if (!isNaN(id)) {
                            var index = {{ $ranId }}.indexOf(id);
                            if (index !== -1) {
                                {{ $ranId }}.splice(index, 1);
                            }
                            $('div[_id={{ $ranId }}] input').val(
                                {{ $ranId }}.join('|'));
                        }
                    });
                },
                success: function(file, response) {
                    if (response.id) {

                        file._removeLink.setAttribute("data-file_id", response.id);
                        {{ $ranId }}.push(response.id);
                        if ({{ $ranId }}.length >= {{ (int) ($maxFiles ?? 1) }}) {
                            myDropzone_{{ $ranId }}.disable();
                        }

                        $('div[_id={{ $ranId }}] input').val({{ $ranId }}.join(
                            '|'));
                    }
                },
                error: function(file, response) {
                    if (typeof response.message !== 'undefined') {
                        toastr.error(response.message);
                    } else {
                        toastr.error("{{ __('Something went wrong') }}");
                    }
                    this.removeFile(file);
                }
            })
        });
    </script>
@endpush
