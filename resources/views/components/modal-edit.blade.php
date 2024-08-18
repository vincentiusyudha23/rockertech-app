@props(['employe'])

<button type="button" class="bg-gradient-info border-0 rounded-2 text-white" style="width: 30px; height: 30px;" data-toggle="tooltip"
    data-original-title="Edit user" data-bs-toggle="modal" data-bs-target="#edit-modal-{{ $employe->id }}">
    <i class="fa-solid fa-pen-to-square fa-xs"></i>
</button>

<div class="modal fade" id="edit-modal-{{ $employe->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
                        <form class="px-2" action="{{ route('admin.employee.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $employe->id }}">
                            <div class="row">
                                <div class="col-6">
                                    {{-- <div class="form-group mb-2">
                                        <label for="media-image">Image</label>
                                        <input type="file" class="form-control" id="media-image" name="image">
                                    </div> --}}
                                    <x-media-upload name="image" :value="$employe->image"/>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex flex-column justify-content-center">
                                        <div class="form-group mb-2">
                                            <label for="name">Name</label>
                                            <input type="text" class="form-control" id="name" name="name" value="{{ $employe?->name }}">
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="position">Position</label>
                                            <input type="text" class="form-control" id="position" name="position" value="{{ $employe?->position }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $employe?->email }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="mobile">Mobile Number</label>
                                <input type="number" class="form-control" id="mobile" name="mobile" value="{{ $employe?->mobile }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="nik">NIK</label>
                                <input type="number" class="form-control" id="nik" name="nik" value="{{ $employe?->nik }}">
                            </div>
                            <div class="form-group mb-2">
                                <label for="date_birth">Date of Birth</label>
                                <input type="date" class="form-control" id="date_birth" name="date_birth" value="{{ $employe?->birthday->format('Y-m-d') }}">
                            </div>
                            <div class="form-group mb-4">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $employe?->address?->street_address }}"
                                    placeholder="Street Address">
                                <div class="row mt-2">
                                    <div class="col-12 col-md-6">
                                        <input class="form-control" type="text" name="kelurahan" value="{{ $employe?->address?->kelurahan }}"
                                            placeholder="Kelurahan">
                                        <input class="form-control mt-2" type="text" name="kecamatan" value="{{ $employe?->address?->kecamatan }}"
                                            placeholder="Kecamatan">
                                    </div>
                                    <div class="col-12 col-md-6 mt-2 mt-md-0">
                                        <input class="form-control" type="text" name="kota" placeholder="Kota" value="{{ $employe?->address?->kota }}">
                                        <input class="form-control mt-2" type="text" name="provinsi" value="{{ $employe?->address?->provinsi }}"
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