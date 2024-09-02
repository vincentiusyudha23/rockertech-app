@php
    $data = [
        [
            'img' => assets('img/team-1.jpg'),
            'name' => 'Employee 1',
            'time' => '10:00'
        ],

    ];
    
@endphp
<div class="col-12 h-100">
    <div class="card h-100">
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
                <tbody>
                    @if (!empty($precense))
                        @foreach ($precense as $item)
                            <tr>
                                <td>
                                    <div class="d-flex px-2 py-1">
                                        @php
                                            $image = get_data_image($item?->employe?->image ?? '');
                                        @endphp
                                        <div>
                                            <img src="{{ $image['img_url'] ?? ''  }}" class="avatar avatar-sm me-3" alt="{{ $image['alt'] ?? '' }}">
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="mb-0 text-sm">{{ $item?->employe?->name }}</h6>
                                            <p class="text-xs text-secondary mb-2">{{ Carbon\carbon::parse($item->time)->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">STAFF</p>
                                    <p class="text-xs text-secondary mb-0">{{ $item->employe->position }}</p>
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
        {{-- <div style="height: 370px;">
            <div class="w-100" id="precense_list">
                @foreach ($precense as $item) 
                <div class="{{ $loop->index !== 0 ? 'border-top' : '' }} py-3 d-flex justify-content-between px-4">
                    <div class="d-flex align-items-center">
                        @php
                            $image = get_data_image($item?->employe?->image ?? '');
                        @endphp
                        <div>
                            <img src="{{ $image['img_url'] ?? ''  }}" alt="{{ $image['alt'] ?? '' }}" class="avatar avatar-md me-3"/>
                        </div>
                        <div>
                            <p class="p-0 m-0">{{ $item?->employe?->name }}</p>
                            <p class="p-0 m-0 text-xs fw-bold">{{ Carbon\carbon::parse($item->time)->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
                        <span class="text-xs font-weight-bold">
                            Time
                        </span>
                        <span class="text-lg">{{ Carbon\carbon::parse($item->time)->format('H:i') }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div> --}}
    </div>
</div>