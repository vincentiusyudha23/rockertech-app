@if (isset($permits))
    <div class="table-responsive">
        <table class="table align-items-center mb-0" id="permitTable">
            <thead>
                <tr>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</th>
                    <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">From Date</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">To Date</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Type</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permits as $permit)
                    <tr>
                        <td class="text-bold text-center">{{ $loop->index + 1 }}.</td>
                        <td>
                            <img alt="image" class="avatar avatar-sm me-2" src="{{ get_data_image($permit->employe->image)['img_url'] }}">
                            <span class="text-sm">{{ $permit->employe->name }}</span>
                        </td>
                        <td class="text-center">{{ $permit->from_date->format('D, d M Y') }}</td>
                        <td class="text-center">{{ $permit->to_date->format('D, d M Y') }}</td>
                        <td class="text-center">{!! \App\Enums\PermitTypeEnum::from($permit->type)->badgeType() !!}</td>
                        <td class="text-center">{!! \App\Enums\PermitStatusEnum::from($permit->status)->badgeStatus() !!}</td>
                        <td class="d-flex align-items-center justify-content-center gap-1">
                            <button class="btn btn-icon bg-gradient-info m-0 px-3 py-2" data-bs-toggle="modal" data-bs-target="#modal-permit-{{ $permit->id }}">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <form action="{{ route('admin.permit.delete', ['id' => $permit->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-icon bg-gradient-danger m-0 px-3 py-2">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                            <div class="modal fade" id="modal-permit-{{ $permit->id }}">
                                <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="w-100 d-flex justify-content-start align-items-center gap-1">
                                                <img alt="img" class="avatar avatar-xl me-2" src="{{ get_data_image($permit->employe->image)['img_url'] }}">
                                                <div>
                                                    <h5 class="font-weight-bolder text-lg m-0 p-0">{{ $permit->employe->name }}</h5>
                                                    <p class="m-0 p-0">{{ $permit->employe->position }}</p>
                                                </div>
                                            </div>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row mb-2 px-3 py-2">
                                                <div class="col-12 col-md-6 px-2 mb-2">
                                                    <p class="text-sm m-0 p-0">Permit Type</p>
                                                    <span class="font-weight-bolder text-sm">{{ \App\Enums\PermitTypeEnum::from($permit->type)->labelType() }}</span>
                                                </div>
                                                <div class="col-12 col-md-6 px-2 mb-2">
                                                    <p class="text-sm m-0 p-0">Status</p>
                                                    <span class="font-weight-bolder text-sm">{{ \App\Enums\PermitStatusEnum::from($permit->status)->labelStatus() }}</span>
                                                </div>
                                                <div class="col-12 col-md-6 px-2 mb-2">
                                                    <p class="text-sm m-0 p-0">From Date</p>
                                                    <span class="font-weight-bolder text-sm">{{ $permit->from_date->format('D, d M Y') }}</span>
                                                </div>
                                                <div class="col-12 col-md-6 px-2 mb-2">
                                                    <p class="text-sm m-0 p-0">To Date</p>
                                                    <span class="font-weight-bolder text-sm">{{ $permit->to_date->format('D, d M Y') }}</span>
                                                </div>
                                                <div class="col-12">
                                                    <div class="w-100 d-flex justify-content-end px-2">
                                                        @if ($permit->file_id)
                                                            <a href="{{ get_data_file($permit->file_id)['file_url'] }}" target="_blank" class="btn btn-icon bg-gradient-info m-0 py-2 px-3 position-relative">
                                                                <i class="fa-solid fa-file text-sm"></i>
                                                                <span class="badge bg-danger badge-sm badge-circle position-absolute z-3" style="top: -7.5px; rigght: -7.5px;">1</span>
                                                            </a>
                                                        @endif
                                                        </div>
                                                </div>
                                                <div class="col-12 px-2 mb-2">
                                                    <p class="text-sm m-0 p-0">Reason</p>
                                                    <p class="m-0 p-0">
                                                        {!! $permit->reason !!}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <form action="{{ route('admin.permit.set-not-approved', ['id' => $permit->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="w-100 btn btn-sm bg-gradient-danger" @if($permit->status == 2) disabled @endif>Not Approved</button>
                                                    </form>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <form action="{{ route('admin.permit.set-approved', ['id' => $permit->id]) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="submit" class="w-100 btn btn-sm bg-gradient-success" @if($permit->status == 3) disabled @endif>Approved</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif

@push('scripts')
    <script>
        $(document).ready(function(){
            $('#permitTable').DataTable({
                scrollX: true,
                responsive: false,
            })
        });
    </script>
@endpush