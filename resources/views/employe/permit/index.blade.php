@extends('layouts.master')

@section('title', 'Permit Submission')

@section('content')
    <x-navbar title_page="Permit Submission">
        <div class="w-100 p-2" x-data="permitForm">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-control-label" for="permit-type">Permit For?<sup class="text-danger">*</sup></label>
                        <div class="input-group">
                            <select class="form-select" x-model="newForm.permitType" id="permit-type" required>
                                <option value="" selected>Select Permit</option>
                                <option value="1">Sick</option>
                                <option value="2">Leave</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="permit-from-date">From Date<sup class="text-danger">*</sup></label>
                                <div class="input-group">
                                    <input type="date" x-model="newForm.fromDate" class="form-control" id="permit-from-date" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="permit-to-date">To Date<sup class="text-danger">*</sup></label>
                                <div class="input-group">
                                    <input type="date" x-model="newForm.toDate" class="form-control" id="permit-to-date" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label" for="permit-file">File Upload <small class="text-xxs font-italic text-secondary opacity-9">(Optional)</small></label>
                        <div class="input-group">
                            <input @change="handleFileUpload($event)" class="form-control" type="file" id="permit-file" accept="image/*,.pdf">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label" for="permit-reason">Reason<sup class="text-danger">*</sup></label>
                        <div id="permit-reason" style="height: 100px;"></div>
                    </div>

                    <div class="w-100">
                        <button x-on:click="savePermit()" :disabled="isLoading" class="btn bg-gradient-info w-100 fs-6">
                            <span x-show="!isLoading">Save</span>
                            <template x-if="isLoading">
                                <i class="fa-solid fa-spinner fa-spin"></i>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {

            const newForm = {
                permitType: null,
                fromDate: null,
                toDate: null,
                reason: null,
                file: null
            };

            Alpine.data('permitForm', () => ({
                newForm: newForm,
                quillPermitReason: null,
                isLoading: false,
                handleFileUpload(event){
                    const file = event.target.files[0];
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf'];
                    if (file) {
                        
                        if (!validTypes.includes(file.type)) {
                            toastr.error('Invalid File Format', 'Only accept image files (JPEG, PNG, GIF) and PDF');
                            event.target.value = '';
                            this.newForm.file = null;
                            return;
                        }
                        
                        if (file.size > 5 * 1024 * 1024) {
                            toastr.error('File is too large', 'Maximum file size 5MB');
                            event.target.value = '';
                            this.newForm.file = null;
                            return;
                        }
                        
                        this.newForm.file = file;
                    } else {
                        this.newForm.file = null;
                    }
                },
                async savePermit(){
                    const $this = this;
                    $this.isLoading = true;
                    
                    try {
                        const formData = new FormData();

                        if($this.newForm.file){
                            formData.append('file', $this.newForm.file);
                        }

                        if($this.newForm.permitType) formData.append('permitType', $this.newForm.permitType);
                        if($this.newForm.fromDate) formData.append('from_date', $this.newForm.fromDate);
                        if($this.newForm.toDate) formData.append('to_date', $this.newForm.toDate);
                        if($this.newForm.reason) formData.append('reason', $this.newForm.reason);

                        const response = await fetch('{{ route('employe.permit.store') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const res = await response.json();

                        if(response.ok && res.type == 'success'){
                            setTimeout(() => {
                                Swal.fire({
                                    title: 'Success',
                                    icon: 'success',
                                    text: 'Send Permit Submission is Successfully!'
                                }).then((result) => {
                                    if(result){
                                        $this.newForm = {
                                            permitType: null,
                                            fromDate: null,
                                            toDate: null,
                                            reason: null,
                                            file: null
                                        };

                                        $('#permit-file').val(null);
                                        $this.quillPermitReason.root.innerHTML = '';
                                        $this.isLoading = false;
                                    }
                                });
                            }, 1000);

                        } else {
                            const errors = res.errors;
                            Object.values(errors).forEach(error => {
                                toastr.error(error);
                            });
                            $this.isLoading = false;
                        }
                    } catch (err) {
                        console.log(err);
                        $this.isLoading = false;
                    }
                },
                init(){
                    this.$nextTick(() => {
                        this.quillPermitReason = new Quill('#permit-reason', {
                            theme: 'snow',
                            modules: { toolbar: true }
                        });

                        this.quillPermitReason.on('text-change', () => {
                            this.newForm.reason = this.quillPermitReason.root.innerHTML;
                        });
                    })
                }
            }))
        });
    </script>
@endpush