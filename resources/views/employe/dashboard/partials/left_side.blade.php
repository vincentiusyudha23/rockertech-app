<div class="card">
    <div class="card-body">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="image-tab" role="tabpanel" aria-labelledby="image_tab">
                <div class="d-flex flex-column align-items-center justify-content-center w-100">
                    <img src="{{ assets('img/work_from_home.jpg') }}" id="image-preview"
                        style="width: 350px; height: 275px; object-fit: cover;">
                    <div class="text-center mb-2">
                        <p class="fw-bold h5 p-0 m-0">Work From Home</p>
                        <p class="p-0 m-0 fw-bold text-xs">"Upload Your Image"</p>
                    </div>
                    <input type="file" class="d-none" id="file-input" accept="image/*" capture="environment">
                    <input type="hidden" id="image-save" value="">
                    <div class="d-flex gap-2">
                        <a href="javascript:void(0)" id="choose-btn" class="btn btn-sm bg-gradient-info">Choose File</a>
                        <a href="javascript:void(0)" id="save-btn" class="btn btn-sm bg-gradient-success d-none">Save</a>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade h-100" id="photo-tab" role="tabpanel" aria-labelledby="photo_tab">
                <div class="d-flex justify-content-center align-items-center rounded-3" style="max-width: 100%; overflow: hidden;">
                    <div class="bg-dark" id="camera-feed" style="transform: scaleX(-1);"></div>
                </div>
                <div class="d-flex justify-content-center w-100 mt-2">
                    <a href="javascript:void(0)" id="take-photo" class="btn btn-sm bg-gradient-info">Take Photo</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="card mt-2">
    <div class="card-body">
        <div class="nav-wrapper position-relative end-0">
            <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1 active fw-bold text-sm" data-bs-toggle="tab"
                        data-bs-target="#image-tab" id="image_tab" href="javascript:;" role="tab"
                        aria-selected="true">
                        Upload Image
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1 fw-bold text-sm" id="photo_tab" data-bs-toggle="tab"
                        href="javascript:;" role="tab" aria-selected="true" data-bs-target="#photo-tab">
                        Camera
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="card mt-2">
    <div class="card-body">
        <figure class="mt-3">
            <blockquote class="blockquote text-center">
                <p class="ps-2">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer posuere erat a ante.
                </p>
            </blockquote>
            <figcaption class="blockquote-footer text-center">
                Someone famous in <cite title="Source Title">Source Title</cite>
            </figcaption>
        </figure>
    </div>
</div>
