<div class="card">
    <div class="card-body">
        <div class="d-flex flex-column align-items-center justify-content-center w-100">
            <img src="{{ assets('img/work_from_home.jpg') }}" id="image-preview"
                style="width: 100%; height: 275px; object-fit: cover;">
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
</div>

<div class="card mt-2">
    <div class="card-body">
        <figure class="mt-3">
            <blockquote class="blockquote text-center">
                <p class="ps-2">{{ get_static_option('quote_employe') }}</p>
            </blockquote>
            <figcaption class="blockquote-footer text-center">
                CEO Rocker Tech<cite title="Source Title"> Nando Ario</cite>
            </figcaption>
        </figure>
    </div>
</div>
