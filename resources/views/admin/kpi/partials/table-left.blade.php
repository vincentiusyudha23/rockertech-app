<div class="card">
    <div class="card-header pb-0">
        <div class="nav-wrapper position-relative end-0">
            <ul class="nav nav-underline">
                <li class="nav-item">
                    <a class="text-sm text-bold opacity-8 nav-link text-secondary active" href="javascript:;" role="tab" aria-selected="true" data-bs-toggle="tab" data-bs-target="#todolist">TodoList</a>
                </li>
                <li class="nav-item">
                    <a class="text-sm text-bold opacity-8 nav-link text-secondary" href="javascript:;" role="tab" aria-selected="true" data-bs-toggle="tab" data-bs-target="#precense">Presence & Discipline</a>
                </li>
                <li class="nav-item">
                    <a class="text-sm text-bold opacity-8 nav-link text-secondary" href="javascript:;" role="tab" aria-selected="true" data-bs-toggle="tab" data-bs-target="#target-achiev">Target Achievment</a>
                </li>
                <li class="nav-item">
                    <a class="text-sm text-bold opacity-8 nav-link text-secondary" href="javascript:;" role="tab" aria-selected="true" data-bs-toggle="tab" data-bs-target="#initiative">Initiative & Collaboration</a>
                </li>
                <li class="nav-item">
                    <a class="text-sm text-bold opacity-8 nav-link text-secondary" href="javascript:;" role="tab" aria-selected="true" data-bs-toggle="tab" data-bs-target="#total-kpi">Total KPI</a>
                </li>
            </ul>
        </div>
    </div>
    <div class="card-body px-0 pb-2">
        <div class="tab-content">
            <div class="tab-pane fade show active" id="todolist" role="tabpanel" aria-labelledby="todolist-tab">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employe Name</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Task</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Task Completed</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($todolists))
                                @foreach ($todolists ?? [] as $todolist)  
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ $todolist['image'] }}" class="avatar avatar-sm me-3" alt="xd">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $todolist['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $todolist['totalTask'] }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $todolist['totalDone'] }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $todolist['nilaiAkhir'] }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress-wrapper w-75 mx-auto">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">{{ $todolist['percentage'] }}%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-{{ $todolist['color'] }}" role="progressbar" style="width: {{ $todolist['percentage'] }}%" aria-valuenow="{{ $todolist['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="precense" role="tabpanel" aria-labelledby="precense-tab">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employe Name</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Presence</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total OnTime</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($precenses))
                                @foreach ($precenses ?? [] as $precense)  
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ $precense['image'] }}" class="avatar avatar-sm me-3" alt="xd">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $precense['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $precense['totalPrecense'] }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $precense['totalOnTime'] }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $precense['nilaiAkhir'] }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress-wrapper w-75 mx-auto">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">{{ $precense['percentage'] }}%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-{{ $precense['color'] }}" role="progressbar" style="width: {{ $todolist['percentage'] }}%" aria-valuenow="{{ $todolist['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="target-achiev" role="tabpanel" aria-labelledby="target-achiev-tab">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employe Name</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Achievment</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Target Achievment</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($achiev))
                                @foreach ($achiev ?? [] as $item)  
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ get_data_image($item['image'])['img_url'] }}" class="avatar avatar-sm me-3" alt="xd">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $item['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $item['total_achiev'] }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        @php
                                            $total = 0;
                                            if($item['position'] == 1){
                                                $total = get_static_option('target_content', 0);
                                            } else if($item['position'] == 2){
                                                $total = get_static_option('target_design', 0);
                                            } else if($item['position'] == 4){
                                                $total = get_static_option('target_client', 0);
                                            } else {
                                                $total = get_static_option('target_closing', 0);
                                            }
                                        @endphp
                                        <span class="text-xs font-weight-bold">{{ $total }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $item['target_achiev'] }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="progress-wrapper w-75 mx-auto">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">{{ $item['percentage'] }}%</span>
                                                </div>
                                            </div>
                                            <div class="progress">
                                                <div class="progress-bar bg-gradient-{{ $item['color'] }}" role="progressbar" style="width: {{ $item['percentage'] }}%" aria-valuenow="{{ $item['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="initiative" role="tabpanel" aria-labelledby="initiative-tab">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employe Name</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Initiative/Contribute</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total Task</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Score</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($initiative))
                                @foreach ($initiative ?? [] as $item)  
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ $item['image'] }}" class="avatar avatar-sm me-3" alt="xd">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $item['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $item['total_comment'] }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $item['total_todo'] }}</span>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $item['score'] }}</span>
                                    </td>
                                    <td class="align-middle w-75">
                                        <div class="progress-wrapper w-100 mx-auto">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">{{ $item['percentage'] }}%</span>
                                                </div>
                                            </div>
                                            <div class="progress w-100">
                                                <div class="progress-bar bg-gradient-{{ $item['color'] }}" role="progressbar" style="width: {{ $item['percentage'] }}%" aria-valuenow="{{ $item['percentage'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="total-kpi" role="tabpanel" aria-labelledby="total-kpi-tab">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Employe Name</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Final Score</th>
                                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($employes))
                                @foreach ($employes ?? [] as $item)  
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ get_data_image($item['image'])['img_url'] }}" class="avatar avatar-sm me-3" alt="xd">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $item['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="text-xs font-weight-bold">{{ $item['final_score'] }}</span>
                                    </td>
                                    <td class="align-middle w-75">
                                        <div class="progress-wrapper w-100 mx-auto">
                                            <div class="progress-info">
                                                <div class="progress-percentage">
                                                    <span class="text-xs font-weight-bold">{{ $item['final_score'] }}%</span>
                                                </div>
                                            </div>
                                            <div class="progress w-100">
                                                <div class="progress-bar bg-gradient-{{ $item['color'] }}" role="progressbar" style="width: {{ $item['final_score'] }}%" aria-valuenow="{{ $item['final_score'] }}" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
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
</div>