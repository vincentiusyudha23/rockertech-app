<div class="row" style="height: 300px;">
    <div class="col-12 col-md-6 h-100">
        <div class="card h-100">
            <div class="card-body h-100 d-flex flex-column justify-content-center align-items-center">
                <x-donat-chart :total="$total_precense"/>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 h-100">
        <div class="card h-100">
            <div class="card-body">
                <div class="card-body h-100 d-flex flex-column justify-content-center align-items-center">
                    <x-donat-chart total="8"/>
                </div>
            </div>
        </div>
    </div>
</div>