<div class="row" style="height: 300px;">
    <div class="col-12 col-md-6 h-100">
        <div class="card h-100">
            <div class="card-body h-100 d-flex flex-column justify-content-center align-items-center">
                <x-donat-chart :total="$total_precense" :key_num="$days" title="This Month"/>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6 h-100">
        <div class="card h-100">
            <div class="card-body">
                <div class="card-body h-100 d-flex flex-column justify-content-center align-items-center">
                    <x-donat-chart :total="$total_this_week" key_num="6" title="This Week"/>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center pb-2">
                <span class="text-center text-secondary font-weight-bolder opacity-7">Recent's Precense</span>
                <div class="text-center text-secondary font-weight-bolder opacity-7">
                    <i class="fa-solid fa-calendar-days pe-2"></i>
                    <span>{{ Carbon\Carbon::now()->format('d/m/Y') }}</span>
                </div>
            </div>
        <div class="card-body px-0 pt-0 pb-2" id="sidenav-scrollbar">
            <div class="table-responsive p-0">
                <table class="table align-items-center mb-0" id="table-recent">
                    <thead>
                        <tr>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Position</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                            <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Time</th>
                        </tr>
                    </thead>
                    <tbody class="px-2">
                        @if (!empty($precense))
                            @foreach ($precense as $item)
                                <tr>
                                    <td class="align-middel text-start">
                                        <div>
                                            <h6 class="m-0 p-0 text-sm">{{ $item?->employe?->name }}</h6>
                                            <p class="m-0 p-0 text-xs text-secondary">{{ Carbon\carbon::parse($item->time)->diffForHumans() }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="m-0 p-0 text-xs font-weight-bold mb-0">STAFF</p>
                                        <p class="m-0 p-0 text-xs text-secondary mb-0">{{ $item->employe->position }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm"> 
                                        {!! labelStatus($item->status) !!}
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-sm font-weight-bold">{{ $item->time }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>