<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom mx-3 p-0 pt-4 pb-1 px-2">
                <h5 class="modal-title font-weight-bold text-secondary" id="exampleModalLabel">Employee Account</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <form class="px-2" action="{{ route('admin.employee.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-6">
                                    {{-- <div class="form-group mb-2">
                                        <label for="media-image">Image</label>
                                        <input type="file" class="form-control" id="media-image" name="image">
                                    </div> --}}
                                    <x-media-upload name="image"/>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex flex-column justify-content-center">
                                        <div class="form-group mb-2">
                                            <label for="name">Name<sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="position">Position<sup class="text-danger">*</sup></label>
                                            <select class="form-select" name="position" required>
                                                <option value="">Select Positon</option>
                                                <option value="1">Content Planner</option>
                                                <option value="2">Designer</option>
                                                <option value="3">Business Admin</option>
                                                <option value="4">Sales</option>
                                                <option value="5">Assistant Manager</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label for="email">Email<sup class="text-danger">*</sup></label>
                                <input type="email" class="form-control" id="email" required name="email" value="{{ old('email') }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="mobile">Mobile Number<sup class="text-danger">*</sup></label>
                                <input type="number" class="form-control" id="mobile" required name="mobile" value="{{ old('mobile') }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="nik">NIK<sup class="text-danger">*</sup></label>
                                <input type="number" class="form-control" id="nik" required name="nik" value="{{ old('nik') }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="date_birth">Date of Birth<sup class="text-danger">*</sup></label>
                                <input type="date" class="form-control" id="date_birth" required name="date_birth" value="{{ old('date_birth') }}">
                            </div>
                            <div class="form-group mb-4">
                                <label for="address">Address <small class="text-xxs font-italic text-secondary opacity-9">(Optional)</small></label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}"
                                    placeholder="Street Address">
                                <div class="row mt-2">
                                    <div class="col-12 col-md-6">
                                        <input class="form-control" type="text" name="kelurahan" value="{{ old('kelurahan') }}"
                                            placeholder="Kelurahan">
                                        <input class="form-control mt-2" type="text" name="kecamatan" value="{{ old('kecataman') }}"
                                            placeholder="Kecamatan">
                                    </div>
                                    <div class="col-12 col-md-6 mt-2 mt-md-0">
                                        <input class="form-control" type="text" name="kota" placeholder="Kota" value="{{ old('kota') }}">
                                        <input class="form-control mt-2" type="text" name="provinsi" value="{{ old('provinsi') }}"
                                            placeholder="Provinsi">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-2">
                                {{-- <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button> --}}
                                <button type="submit" class="btn bg-gradient-info w-100">Save changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body vh-50">
                <div class="w-100 h-100 d-flex justify-content-center align-items-center">
                    <button type="button" class="btn btn-info" id="btn-test">test</button>
                    <div class="d-flex flex-column text-center base-tap" id="base-tap">
                        <i class="tap-item tap-item-1 fa-solid fa-address-card text-success"></i>
                        <i class="tap-item tap-item-2 fa-solid fa-arrow-down fa-bounce"></i>
                        <span class="fw-bold fs-4">TAP YOUR CARD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
