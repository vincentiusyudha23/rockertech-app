@if (isset($todolist))
    <div class="table-responsive">
        <table class="table align-items-center mb-0" id="todolistTable">
            <thead>
                <tr>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</th>
                    <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                    <th class="text-start text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Assign To</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Due Date</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Priority</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($todolist as $item)
                    <tr>
                        <td class="text-center">{{ $loop->index + 1 }}</td>
                        <td class="text-start">{{ $item->title }}</td>
                        <td class="text-start">
                            <img alt="image" class="avatar avatar-sm me-2" src="{{ get_data_image($item->employe->image)['img_url'] }}">
                            <span class="text-sm">{{ $item->employe->name }}</span>
                        </td>
                        <td class="text-center">{{ $item->due_date->format('D, d M Y') }}</td>
                        <td class="text-center">
                            @if ($item->priority == 1)
                                <span class="badge badge-sm bg-gradient-info">Low</span>
                            @endif
                            @if ($item->priority == 2)
                                <span class="badge badge-sm bg-gradient-success">Normal</span>
                            @endif
                            @if ($item->priority == 3)
                                <span class="badge badge-sm bg-gradient-danger">High</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if ($item->status == 1)
                                <div class="badge badge-pill border-secondary border" style="scale: 0.85; background-color: rgba(108, 117, 125, 0.2);">
                                    <span class="text-secondary">Pending</span>
                                </div>
                            @endif
                            @if ($item->status == 2)
                                <div class="badge badge-pill border-info border" style="scale: 0.85; background-color: rgba(23, 162, 184, 0.2);">
                                    <span class="text-info">On Progress</span>
                                </div>
                            @endif
                            @if ($item->status == 3)
                                <div class="badge badge-pill border-warning border" style="scale: 0.85; background-color: rgba(255, 193, 7, 0.2);">
                                    <span class="text-warning">Checking</span>
                                </div>
                            @endif
                            @if ($item->status == 4)
                                <div class="badge badge-pill border-success border" style="scale: 0.85; background-color: rgba(40, 167, 69, 0.2);">
                                    <span class="text-success">Done</span>
                                </div>
                            @endif
                        </td>
                        <td class="d-flex justify-content-center">
                            <button class="btn btn-icon bg-gradient-info m-0 py-1 px-3" data-bs-toggle="modal" data-bs-target="#modal-todlist-{{ $item->id }}">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </button>
                            <div class="modal fade" id="modal-todlist-{{ $item->id }}">
                                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class="d-flex align-items-center gap-2">
                                                <h5 class="modal-title font-weight-normal">To-do List</h5>
                                                @if ($item->priority == 1)
                                                    <span class="badge badge-sm bg-gradient-info">Low</span>
                                                @endif
                                                @if ($item->priority == 2)
                                                    <span class="badge badge-sm bg-gradient-success">Normal</span>
                                                @endif
                                                @if ($item->priority == 3)
                                                    <span class="badge badge-sm bg-gradient-danger">High</span>
                                                @endif
                                                @if ($item->status == 1)
                                                    <div class="badge badge-pill border-secondary border" style="scale: 0.85; background-color: rgba(108, 117, 125, 0.2);">
                                                        <span class="text-secondary">Pending</span>
                                                    </div>
                                                @endif
                                                @if ($item->status == 2)
                                                    <div class="badge badge-pill border-info border" style="scale: 0.85; background-color: rgba(23, 162, 184, 0.2);">
                                                        <span class="text-info">On Progress</span>
                                                    </div>
                                                @endif
                                                @if ($item->status == 3)
                                                    <div class="badge badge-pill border-warning border" style="scale: 0.85; background-color: rgba(255, 193, 7, 0.2);">
                                                        <span class="text-warning">Checking</span>
                                                    </div>
                                                @endif
                                                @if ($item->status == 4)
                                                    <div class="badge badge-pill border-success border" style="scale: 0.85; background-color: rgba(40, 167, 69, 0.2);">
                                                        <span class="text-success">Done</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-2 d-flex justify-content-between align-items-center px-2">
                                                <div>
                                                    <div class="mb-1">
                                                        <span class="text-sm">Assigned To:</span>
                                                    </div>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <a href="javascript:;" class="avatar avatar-sm">
                                                            <img alt="image" src="{{ get_data_image($item->employe->image)['img_url'] }}">
                                                        </a>
                                                        <span class="text-sm">{{ $item->employe->name }}</span>
                                                    </div>
                                                </div>
                                                <div class="pt-3">
                                                    @if ($item->status == 4)
                                                        <button type="button" class="btn btn-sm bg-gradient-success d-flex gap-1 justify-content-center align-items-center disabled" data-id="{{ $item->id }}" style="width: 200px;">
                                                            <i class="fa-solid fa-check text-xs"></i> <span class="text-xs">Done</span>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm bg-gradient-success d-flex gap-1 justify-content-center align-items-center btn-completed" data-id="{{ $item->id }}" style="width: 200px;">
                                                            <span class="text-xs">Set as Completed / Done</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label" for="edit-title">Title</label>
                                                <div class="input-group">
                                                    <input class="form-control" readonly type="text" aria-describedby="basic-addon3" name="edit-title" value="{{ $item->title }}">
                                                </div>    
                                            </div>

                                            <div class="row">
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="edit-priority">Priority</label>
                                                        <div class="input-group">
                                                            <input type="text" readonly class="form-control" value="{{ \App\Enums\TodolistPriorityEnum::from($item->priority)->label() }}">
                                                        </div>    
                                                    </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="edit-due-date">Due Date</label>
                                                        <div class="input-group">
                                                            <input type="date" value="{{ $item->due_date->format('Y-m-d') }}" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="form-control-label">Description</label>
                                                <div style="height: 150px;" id="quill-display-{{ $item->id }}"></div>
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
            $('#todolistTable').DataTable({
                scrollX: true,
                responsive: false,
            });

            const todolist = @json($todolist);
            todolist.forEach(function(item){
                var quill = new Quill('#quill-display-' + item.id, {
                    readOnly: true,
                    theme: 'snow'
                });

                quill.root.innerHTML = item.desc;
            });
            
            $('.btn-completed').on('click', function(e){
                e.preventDefault();
                let el = $(this);
                let id = el.data('id');

                $.ajax({
                    url: '{{ route("admin.todolist.completed") }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    beforeSend: function(){
                        el.addClass('disabled');
                        el.html('<i class="fa-solid fa-spinner text-xs fa-spin"></i>');
                    },
                    success: function(res){
                        if(res.type == 'success'){
                            location.reload();
                        }
                    },
                    error: function(err){
                        el.removeClass('disabled');
                        el.text('SAVE');
                    }
                });
            })
        });
    </script>
@endpush