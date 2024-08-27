@php
    $data = [
        [
            'img' => assets('img/team-1.jpg'),
            'name' => 'Employee 1',
            'time' => '10:00'
        ],

    ];
    
@endphp
<div class="col-lg-5 h-100">
    <div class="card h-100">
        <div class="card-header pb-0">
            <h6>Recent's Precense</h6>
        </div>
    <div class="card-body px-0" id="sidenav-scrollbar">
        <div style="height: 370px;">
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
        </div>
    </div>
</div>