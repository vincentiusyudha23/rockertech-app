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
                        <form class="px-2" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-6">
                                    {{-- <div class="form-group mb-2">
                                        <label for="media-image">Image</label>
                                        <input type="file" class="form-control" id="media-image" name="image">
                                    </div> --}}
                                    <x-media-upload name="image"/>
                                </div>
                                <div class="col-6">
                                    <div class="form-group mb-2">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name">
                                    </div>
                                    <div class="form-group mb-2">
                                        <label for="position">Position</label>
                                        <input type="text" class="form-control" id="position" name="position">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-2">
                                <label for="nik">NIK</label>
                                <input type="number" class="form-control" id="nik" name="nik">
                            </div>
                            <div class="form-group mb-2">
                                <label for="date_birth">Date of Birth</label>
                                <input type="date" class="form-control" id="date_birth" name="date_birth">
                            </div>
                            <div class="form-group mb-2">
                                <label for="address">Address</label>
                                <input type="text" class="form-control" id="address" name="address"
                                    placeholder="Street Address">
                                <div class="row mt-2">
                                    <div class="col-12 col-md-6">
                                        <input class="form-control" type="text" name="kelurahan"
                                            placeholder="Kelurahan">
                                        <input class="form-control mt-2" type="text" name="kecamatan"
                                            placeholder="Kecamatan">
                                    </div>
                                    <div class="col-12 col-md-6 mt-2 mt-md-0">
                                        <input class="form-control" type="text" name="kota" placeholder="Kota">
                                        <input class="form-control mt-2" type="text" name="provinsi"
                                            placeholder="Provinsi">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="px-4 mb-2">
                {{-- <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button> --}}
                <button type="button" class="btn bg-gradient-info w-100">Save changes</button>
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
                    <div class="d-flex flex-column text-center base-tap">
                        <i class="tap-item tap-item-1 fa-solid fa-address-card text-success"></i>
                        <i class="tap-item tap-item-2 fa-solid fa-arrow-down fa-bounce"></i>
                        <span class="fw-bold fs-4">TAP YOUR CARD</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
