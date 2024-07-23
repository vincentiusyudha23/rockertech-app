@php
    $data = [
        [
            'img' => assets('img/team-1.jpg'),
            'name' => 'Employee 1',
            'time' => '10:00'
        ],
        [
            'img' => assets('img/team-2.jpg'),
            'name' => 'Employee 2',
            'time' => '10:10'
        ],
        [
            'img' => assets('img/team-3.jpg'),
            'name' => 'Employee 3',
            'time' => '10:20'
        ],
        [
            'img' => assets('img/team-4.jpg'),
            'name' => 'Employee 4',
            'time' => '10:30'
        ],
        [
            'img' => assets('img/team-4.jpg'),
            'name' => 'Employee 5',
            'time' => '10:30'
        ],
        [
            'img' => assets('img/team-4.jpg'),
            'name' => 'Employee 6',
            'time' => '10:30'
        ],
        [
            'img' => assets('img/team-4.jpg'),
            'name' => 'Employee 7',
            'time' => '10:30'
        ]

    ];
    
@endphp
<div class="col-lg-5 h-100">
    <div class="card h-100">
        <div class="card-header pb-0">
            <h6>Recent's Precense</h6>
        </div>
    <div class="card-body px-0" id="sidenav-scrollbar">
        <div style="height: 370px;">
            <div class="w-100">
                @foreach ($data as $item) 
                <div class="{{ $loop->index !== 0 ? 'border-top' : '' }} py-3 d-flex justify-content-between px-4">
                    <div class="d-flex align-items-center">
                        <div>
                            <img src="{{ $item['img'] }}" alt="avatar" class="avatar avatar-sm me-3 rounded-circle"/>
                        </div>
                        {{ $item['name'] }}
                    </div>
                    <div class="d-flex flex-column justify-content-center align-items-center" style="height: 100%;">
                        <span class="text-xs font-weight-bold">
                            Time
                        </span>
                        <span class="text-lg">{{ $item['time'] }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>