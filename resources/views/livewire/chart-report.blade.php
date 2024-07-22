<div class="col-lg-7">
    <div class="card z-index-2">
        <div class="card-header pb-0">
            <h6>Presence Activity</h6>
            <p class="text-sm">
                <i class="fa fa-arrow-up text-success"></i>
                <span class="font-weight-bold">4% more</span> in 2021
            </p>
        </div>
        <div class="card-body p-3" style="height: 300px;">
            <div wire:loading id="skeleton" class="skeleton">
                <div class="skeleton-text mt-2"></div>
            </div>
            <div wire:loading.remove class="chart" style="height: 100%;">
                <canvas id="chart-line" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>
</div>

<script src="{{ assets('js/plugins/chartjs.min.js') }}"></script>
