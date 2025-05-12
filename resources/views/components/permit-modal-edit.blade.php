@if (isset($permit) && !empty($permit))
    <button class="btn btn-icon btn-sm bg-gradient-info m-0 px-3 py-2" id="btn-edit-modal" data-bs-toggle="modal" data-bs-target="#edit-permit-{{ $permit->id }}">
        <i class="fa-solid fa-eye text-sm"></i>
    </button>
    <div class="modal fade" id="edit-permit-{{ $permit->id }}">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-normal">Edit Permit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <form action="{{ route('employe.permit.update') }}" method="POST" enctype="multipart/form-data" id="edit-form-{{ $permit->id }}">
                        @csrf
                        <input type="hidden" name="id" value="{{ $permit->id }}">
                        <div class="form-group">
                            <label class="form-control-label" for="edit-type-{{ $permit->id }}">Permit For?<sup class="text-danger">*</sup></label>
                            <div class="input-group">
                                <select class="form-select" required id="edit-type-{{ $permit->id }}" name="permitType">
                                    <option value="">Select Permit</option>
                                    <option value="1" {{ $permit->type == 1 ? 'selected' : '' }}>Sick</option>
                                    <option value="2" {{ $permit->type == 2 ? 'selected' : '' }}>Leave</option>
                                </select>
                            </div>
                        </div>
    
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="edit-from-{{ $permit->id }}">From Date<sup class="text-danger">*</sup></label>
                                    <div class="input-group">
                                        <input required type="date" value="{{ $permit->from_date->format('Y-m-d') }}" name="from_date" class="form-control" id="edit-from-{{ $permit->id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="edit-to-{{ $permit->id }}">To Date<sup class="text-danger">*</sup></label>
                                    <div class="input-group">
                                        <input required type="date" value="{{ $permit->to_date->format('Y-m-d') }}" name="to_date" class="form-control" id="edit-to-{{ $permit->id }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-control-label" for="edit-permit-file-{{ $permit->id }}">File Upload <small class="text-xxs font-italic text-secondary opacity-9">(Optional)</small></label>
                            <div class="input-group">
                                <input class="form-control" type="file" id="edit-permit-file-{{ $permit->id }}" name="file" accept="image/*,.pdf">
                            </div>
                            @if ($permit?->file_id)
                                <div class="w-100 d-flex justify-content-center align-items-center p-3">
                                    <a href="{{ $permit->file['file_url'] }}" target="_blank" class="text-center">
                                        <i class="fa-regular fa-file fs-1"></i>
                                        <p class="m-0 p-0">{{ $permit->file['title'] }}</p>
                                    </a>
                                </div>
                            @endif
                        </div>
    
                        <div class="form-group">
                            <label class="form-control-label" for="edit-reason-{{ $permit->id }}">Reason<sup class="text-danger">*</sup></label>
                            <div>
                                <div class="edit-reason" style="height: 150px;" id="edit-reason-{{ $permit->id }}" data-form="#edit-form-{{ $permit->id }}" data-button="#btn-save-{{ $permit->id }}" data-id="edit-reason-{{ $permit->id }}" data-val="{{ $permit->reason }}"></div>
                                <input type="hidden" required name="reason" value="{{ $permit->reason }}">
                            </div>
                        </div>
    
                        <div>
                            <button type="submit" id="btn-save-{{ $permit->id }}" class="btn bg-gradient-info w-100 text-sm">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif