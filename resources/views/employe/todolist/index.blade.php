@extends('layouts.master')

@section('title', 'To-do List')

@push('styles')
    <style>
        .todolist-card{
            min-width: 300px;
            max-width: 300px;
        }
        .todolist-parent{
            max-width: 100%;
            height: 85dvh;
        }
        .list-todolist{
            max-width: 100% !important;
            height: 100%;
            overflow-y: auto;
        }
        .hide-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: rgba(0, 0, 0, 0.2) transparent;
        }
        .hide-scrollbar::-webkit-scrollbar {
            width: 8px; /* Adjust scrollbar width */
        }
        .hide-scrollbar::-webkit-scrollbar-thumb {
            background-color: rgba(0, 0, 0, 0.2); /* Transparent thumb */
            border-radius: 4px; /* Rounded edges */
        }
        .hide-scrollbar::-webkit-scrollbar-track {
            background: transparent; /* Transparent track */
        }

        .drag-scroll-container {
            cursor: grab; /* Kursor berubah jadi "tangan" */
            user-select: none; /* Mencegah seleksi teks saat drag */
        }

        .drag-scroll-container:active {
            cursor: grabbing; /* Kursor saat aktif drag */
        }
        .with-dot::before {
            content: "•"; /* Simbol bullet point */
            margin-right: 5px; /* Jarak antara titik dan teks */
            color: inherit; /* Warna mengikuti parent */
        }
        .fs-13px{
            font-size: 13px;
        }
        .ui-state-highlight {
            height: 180px;
            background-color: #f8f9fa;
            border: 1px dashed #ccc;
            border-radius: 15px;
        }
        .dragging-active {
            opacity: 0.7;
            transform: scale(1.02);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .sortable{
            min-height: 75%;
        }
        .parent-btn-add{
            z-index: 500;
            position: absolute;
            bottom: 0;
            right: 20px;
        }

        .parent-btn-add > .btn-add-todolist{
            width: 50px;
            height: 50px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
@endpush

@section('content')
    <x-navbar title_page="To-do List">
        <div class="w-100 position-relative" x-data="todoList">
            <div class="parent-btn-add" data-bs-toggle="tooltip" data-bs-custom-class="custom-tooltip" data-bs-title="Add New To-do List" data-bs-placement="top">
                <button class="btn btn-icon bg-gradient-info rounded-circle btn-add-todolist"
                    data-bs-toggle="modal" 
                    data-bs-target="#modal-new-todolist">
                    <i class="fa-solid fa-plus fs-4"></i>
                </button>
            </div>

            <div class="d-flex gap-3 px-2 todolist-parent overflow-x-scroll">
                <template x-for="(status, index) in statusArr" :key="index">
                    <div class="card todolist-card border-1">
                        <div class="card-header pt-3 pb-0 m-0">
                            <div class="text-center">
                                <span class="text-bold fs-5" x-text="status"></span>
                            </div>
                        </div>
                        <div class="card-body list-todolist hide-scrollbar">
                            <ul :id="'status-'+(index + 1)" class="sortable list-unstyled d-flex flex-column gap-3">
                                <template x-if="todolistData">
                                    <template x-for="(item, indexData) in todolistData[index+1]">
                                        <li class="ui-state-highlight item-sortable border rounded shadow-sm bg-white" x-bind:data-todolistId="item.id">
                                            <input type="hidden" :value="item.id" class="todolistId">
                                            <div class="p-2 pb-0">
                                                <div class="d-flex justify-content-end align-items-center">
                                                    <template x-if="item.status == 1">
                                                        <div class="badge badge-pill border-secondary border" style="scale: 0.85; background-color: rgba(108, 117, 125, 0.2);">
                                                            <span class="text-secondary" x-text="status"></span>
                                                        </div>
                                                    </template>
                                                    <template x-if="item.status == 2">
                                                        <div class="badge badge-pill border-info border" style="scale: 0.85; background-color: rgba(23, 162, 184, 0.2);">
                                                            <span class="text-info" x-text="status"></span>
                                                        </div>
                                                    </template>
                                                    <template x-if="item.status == 3">
                                                        <div class="badge badge-pill border-warning border" style="scale: 0.85; background-color: rgba(255, 193, 7, 0.2);">
                                                            <span class="text-warning" x-text="status"></span>
                                                        </div>
                                                    </template>
                                                    <template x-if="item.status == 4">
                                                        <div class="badge badge-pill border-success border" style="scale: 0.85; background-color: rgba(40, 167, 69, 0.2);">
                                                            <span class="text-success" x-text="status"></span>
                                                        </div>
                                                    </template>
                                                </div>
        
                                                <blockquote class="blockquote mb-1">
                                                    <p class="fs-6 ps-2" x-text="item.title"></p>
                                                </blockquote>
                
                                                <div class="mb-1 px-1">
                                                    <span class="fs-13px fw-bold">Created at : <span x-text="item.created_at"></span></span>
                    
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="fw-bold fs-13px">Assigned To:</span>
                                                        <a href="javascript:;" data-bs-toggle="tooltip"  data-bs-placement="bottom" :title="item.name" data-container="body" data-animation="true">
                                                            <img alt="image" class="avatar avatar-xs rounded-circle" :src="item.image">
                                                        </a>
                                                    </div>
                                                </div>
                
                                                <div class="d-flex justify-content-end align-items-center px-1 mt-3 mb-2 gap-2">
                                                    <button x-on:click="deleteTodolist(item.id)" class="border border-danger bg-white text-danger rounded-2" style="width: 50px; height: 33px;">
                                                        <i class="fa-solid fa-trash text-sm"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-secondary m-0" data-bs-toggle="modal" data-bs-target="#modal-edit-todolist" x-on:click="openModalEdit(item)" type="button">
                                                        view
                                                    </button>
                                                </div>
                                            </div>
                                            
                                            <template x-if="item.priority == 1">
                                                <div class="w-100 bg-gradient-info rounded-bottom" style="height: 10px;"></div>
                                            </template>
                                            <template x-if="item.priority == 2">
                                                <div class="w-100 bg-gradient-success rounded-bottom" style="height: 10px;"></div>
                                            </template>
                                            <template x-if="item.priority == 3">
                                                <div class="w-100 bg-gradient-danger rounded-bottom" style="height: 10px;"></div>
                                            </template>
                                        </li>
                                    </template>
                                </template>
                            </ul>
                        </div>
                    </div>
                </template>
            </div>

            <div class="modal fade" id="modal-new-todolist" tabindex="-1" role="dialog" x-ref="modal_create">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-normal">Add New Task</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
    
                            <div class="form-group">
                                <label class="form-control-label" for="title">Title<sup class="text-danger">*</sup></label>
                                <div class="input-group">
                                    <input class="form-control" type="text" x-model="newForm.title" id="title" aria-describedby="basic-addon3">
                                </div>    
                            </div>

                            <div class="form-group">
                                <label class="form-control-label" for="priority">Priority<sup class="text-danger">*</sup></label>
                                <div class="input-group">
                                    <select class="form-select" x-model="newForm.priority" id="priority" aria-describedby="basic-addon3">
                                        <option value="" selected>Select Priority</option>
                                        <option value="1">Low</option>
                                        <option value="2">Normal</option>
                                        <option value="3">High</option>
                                    </select>
                                </div>    
                            </div>

                            <div class="form-group">
                                <label class="form-control-label" for="type-todolist">Type Task</label>
                                <div class="input-group">
                                    <select class="form-select" id="type-todolist" x-model="newForm.type">
                                        <option value="" selected>Select Type Task</option>
                                        <option value="1">New Client</option>
                                        <option value="2">Design</option>
                                        <option value="3">Content</option>
                                        <option value="4">Closing</option>
                                    </select>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <label class="form-control-label" for="quill-desc">Description</label>
                                <div>
                                    <div id="quill-desc" style="height: 150px;"></div>
                                </div>
                            </div>

                            <div class="w-100 mt-3">
                                <button type="button" x-on:click="saveTodolist" :disabled="loadingSaveNew" class="btn btn-sm bg-gradient-info w-100">
                                    <span class="fs-6" x-show="!loadingSaveNew">Save</span>
                                    <i x-show="loadingSaveNew" class="fa-solid fa-spinner fa-spin fs-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="modal-edit-todolist" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="d-flex gap-2 align-items-center">
                                <h5 class="modal-title font-weight-normal">Edit Task</h5>
                                <template x-if="editForm.priority == 1">
                                    <span class="badge bg-gradient-info">Low</span>
                                </template>
                                <template x-if="editForm.priority == 2">
                                    <span class="badge bg-gradient-success">Normal</span>
                                </template>
                                <template x-if="editForm.priority == 3">
                                    <span class="badge bg-gradient-danger">High</span>
                                </template>

                                <template x-if="editForm.status == 1">
                                    <div class="badge badge-pill border-secondary border m-0" style="scale: 0.85; background-color: rgba(108, 117, 125, 0.2);">
                                        <span class="text-secondary">Pending</span>
                                    </div>
                                </template>
                                <template x-if="editForm.status == 2">
                                    <div class="badge badge-pill border-info border m-0" style="scale: 0.85; background-color: rgba(23, 162, 184, 0.2);">
                                        <span class="text-info">On Progress</span>
                                    </div>
                                </template>
                                <template x-if="editForm.status == 3">
                                    <div class="badge badge-pill border-warning border m-0" style="scale: 0.85; background-color: rgba(255, 193, 7, 0.2);">
                                        <span class="text-warning">Checking</span>
                                    </div>
                                </template>
                                <template x-if="editForm.status == 4">
                                    <div class="badge badge-pill border-success border m-0" style="scale: 0.85; background-color: rgba(40, 167, 69, 0.2);">
                                        <span class="text-success">Done</span>
                                    </div>
                                </template>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">

                            <div class="mb-2 d-flex align-items-center gap-2">
                                <span class="font-weight-bolder text-xs text-uppercase">Assigned To:</span>
                                <img alt="image" class="avatar avatar-xs rounded-circle" :src="editForm.image">
                                <span class="font-weight-bolder text-xs text-uppercase" x-text="editForm.name"></span>
                            </div>

                            <div class="form-group">
                                <label class="form-control-label" for="edit-title">Title<sup class="text-danger">*</sup></label>
                                <div class="input-group">
                                    <input class="form-control" x-model="editForm.title" type="text" id="edit-title" aria-describedby="basic-addon3">
                                </div>    
                            </div>

                            <div class="form-group">
                                <label class="form-control-label" for="edit-priority">Priority<sup class="text-danger">*</sup></label>
                                <div class="input-group">
                                    <select class="form-select" x-model="editForm.priority" id="edit-priority" aria-describedby="basic-addon3">
                                        <option value="" selected>Select Priority</option>
                                        <option value="1">Low</option>
                                        <option value="2">Normal</option>
                                        <option value="3">High</option>
                                    </select>
                                </div>    
                            </div>

                            <div class="form-group">
                                <label class="form-control-label" for="edit-type-todolist">Type Task</label>
                                <div class="input-group">
                                    <select class="form-select" id="edit-type-todolist" x-model="editForm.type">
                                        <option value="" selected>Select Type Task</option>
                                        <option value="1">New Client</option>
                                        <option value="2">Design</option>
                                        <option value="3">Content</option>
                                        <option value="4">Closing</option>
                                    </select>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <label class="form-control-label" for="edit-quill-desc">Description</label>
                                <div>
                                    <div id="edit-quill-desc" style="height: 150px;"></div>
                                </div>
                            </div>

                            <div class="w-100 mt-3">
                                <button type="button" x-on:click="saveEditTodolist" :disabled="loadingSaveEdit" class="btn btn-sm bg-gradient-info w-100">
                                    <span x-show="!loadingSaveEdit" class="fs-6">Save</span>
                                    <i x-show="loadingSaveEdit" class="fa-solid fa-spinner fa-spin fs-5"></i>
                                </button>
                            </div>

                            <div class="w-100 mb-2 rounded-3 px-2 py-3 border border-2" style="height: 300px; overflow-y: auto; display: flex; flex-direction: column-reverse;">
                                <template x-if="commentArr && commentArr.length > 0">
                                    <template x-for="(item, index) in commentArr" :key="index">
                                        <div>
                                            <template x-if="item.user_id == userId">
                                                <div class="w-100 mb-2 d-flex justify-content-end align-items-center gap-2">
                                                    <div style="max-width: 70%;" class="text-end">
                                                        <div class="border border-2 rounded-2 py-1 px-2 bg-light">
                                                            <span class="text-xs text-bold" x-text="item.content"></span>
                                                        </div>
                                                        <small class="text-xxs text-bold" x-text="item.name"></small>
                                                        <small class="text-xxs text-uppercase text-bold opacity-7" x-text="item.created_at"></small>
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="item.user_id != userId">
                                                <div class="w-100 mb-2 d-flex justify-content-start align-items-center gap-2">
                                                    <img alt="image" class="avatar avatar-xs rounded-circle" :src="item.image">
                                                    <div style="max-width: 70%;">
                                                        <div class="border border-2 rounded-2 py-1 px-2" >
                                                            <span class="text-xs text-bold" x-text="item.content"></span>
                                                        </div>
                                                        <small class="text-xxs text-bold" x-text="item.name"></small>
                                                        <small class="text-xxs text-uppercase text-bold opacity-7" x-text="item.created_at"></small>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </template>
                            </div>
                            <div class="w-100 position-relative">
                                <div class="input-group">
                                    <input class="form-control" x-on:keydown.enter="handleSendComment" x-model="commentForm.content" type="text" placeholder="Send Comment...">
                                </div>
                                <button :disabled="loadingComment" x-on:click="handleSendComment" style="z-index: 999;" class="btn btn-icon bg-gradient-info position-absolute top-50 end-0 translate-middle-y py-2 px-3 me-2">
                                    <i class="fa-solid fa-paper-plane text-xs"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
    <script>
        const status = [
            "Pending", 
            "On Progress", 
            "Checking", 
            "Done"
        ];
        const newForm = {
            title : null,
            description : null,
            priority : null,
            type: null
        };

        const editForm = {
            todolist_id: null,
            title : null,
            description : null,
            priority : null,
            status: null,
            image: null,
            name: null,
            type: null
        };

        const commentForm = {
            todolist_id: null,
            content: null,
        };

        document.addEventListener('alpine:init', () => {
            Alpine.data('todoList', () => ({
                userId : '{{ Auth::user()->id }}',
                statusArr: status,
                newForm: newForm,
                editForm: editForm,
                commentForm: commentForm,
                commentArr: [],
                todolistData: @json($todolist),
                loadingSaveNew: false,
                newQuillForm: null,
                editQuillForm: null,
                isUpdating: false,
                loadingSaveEdit: false,
                loadingComment: false,
                allComments: @json($allComments),
                async saveTodolist() {
                    this.modal_create = $(this.$refs.modal_create);
                    this.loadingSaveNew = true;

                    if (!this.newForm.title || !this.newForm.priority) {
                        alert('Please fill all required fields');
                        this.loadingSaveNew = false;
                        return;
                    }
                    
                    try {
                        const response = await fetch("{{ route('employe.todolist.store') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                title: this.newForm.title,
                                priority: this.newForm.priority,
                                description: this.newForm.description,
                                type: this.newForm.type
                            })
                        });

                        const res = await response.json();

                        if (!response.ok) {
                            let errors = res.errors;
                            Object.values(errors).forEach(error => {
                                toastr.error(error);
                            });
                            this.loadingSaveNew = false;
                        }

                        if (response.ok && res.status === 'success') {
                            toastr.success(res.msg);
                            this.loadingSaveNew = false;
                            this.newForm = {
                                title: '',
                                priority: '',
                                description: '',
                                type: ''
                            };

                            this.todolistData = res.data;
                            this.newQuillForm.root.innerHTML = '';
                            this.modal_create.modal('hide');
                        }
                    } catch (err) {
                        console.log(err)
                        this.loadingSaveNew = false;
                    }
                },
                async updateIndexes(id){
                    if(this.isUpdating) return;

                    try{
                        this.isUpdating = true;

                        var allItems = $(`#${id}`).find('.item-sortable');
                        var itemsArr = [];
    
                        $.each(allItems, function (index, value){
                            var todoListId = $(this).find('input[type="hidden"].todolistId').val();
                            itemsArr.push({
                                todolist_id : todoListId,
                                index_task : index + 1,
                                status : parseInt(id.match(/\d+/)[0])
                            });
                        });
    
                        const response = await fetch('{{ route("employe.todolist.updateindex") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                data: itemsArr
                            })
                        })
    
                        const res = await response.json();
    
                        if(response.ok && res.status == 'success'){
                            this.todolistData = [];
                            setTimeout(() => {
                                this.todolistData = res.data;
                            }, 50);
                        }
                    } catch (err) {

                    } finally {
                        this.isUpdating = false;
                    }
                },
                intiallizeSortable(id){
                    const $this = this;
                    let startParentId = null;

                    $(id).sortable({
                        connectWith: '.sortable',
                        placeholder: "ui-state-highlight",
                        start: function(event, ui){
                            startParentId = this.id;
                        },
                        receive: function (event, ui) {
                            $this.updateIndexes(this.id);
                            if (ui.sender) {
                                $this.updateIndexes(ui.sender[0].id);
                            }
                        },
                        stop: function (event, ui) {
                            $this.updateIndexes(this.id);
                        }
                    }).disableSelection();
                },
                async saveEditTodolist() {
                    this.loadingSaveEdit = true;

                    if (!this.editForm.title || !this.editForm.priority) {
                        alert('Please fill all required fields');
                        this.loadingSaveEdit = false;
                        return;
                    }
                    
                    try {
                        const response = await fetch("{{ route('employe.todolist.update') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                id: this.editForm.todolist_id,
                                title: this.editForm.title,
                                priority: this.editForm.priority,
                                description: this.editForm.description,
                                type: this.editForm.type
                            })
                        });

                        const res = await response.json();

                        if (!response.ok) {
                            let errors = res.errors;
                            Object.values(errors).forEach(error => {
                                toastr.error(error);
                            });
                            this.loadingSaveEdit = false;
                        }

                        if (response.ok && res.status === 'success') {
                            toastr.success(res.msg);
                            this.loadingSaveEdit = false;
                            this.todolistData = res.data;
                        }
                    } catch (err) {
                        console.log(err)
                        this.loadingSaveEdit = false;
                    }
                },
                async handleSendComment(){
                    this.loadingComment = true;

                    try {
                        const response = await fetch('{{ route("employe.todolist.send-comment") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                todolist_id: this.commentForm.todolist_id,
                                content: this.commentForm.content,
                            })
                        });

                        const res = await response.json();

                        if(response.ok){
                            this.loadingComment = false;
                            this.commentForm.content = null;
                        }
                    } catch(err){}
                },
                openModalEdit(item){
                    this.editForm = {
                        todolist_id: item.id,
                        title : item.title,
                        description : item.description,
                        priority : item.priority,
                        status : item.status,
                        image: item.image,
                        name: item.name,
                        type: item.type
                    };

                    this.commentForm = {
                        todolist_id: item.id,
                        content: null
                    };

                    this.commentArr = this.allComments[item.id];

                    this.editQuillForm.root.innerHTML = item.description;
                },
                deleteTodolist(id){
                    const $this = this;
                    const swalDelete = Swal.mixin({
                        customClass: {
                            confirmButton: "btn bg-gradient-danger btn-sm mx-1",
                            cancelButton: "btn bg-gradient-secondary btn-sm mx-1"
                        },
                        buttonsStyling: false
                    });

                    swalDelete.fire({
                        title: 'Are you sure?',
                        text: 'You will delete this Todo-list',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: "Yes, delete it!",
                        cancelButtonText: "No, cancel!",
                        reverseButtons: true
                    }).then((result) => {
                        if(result.isConfirmed){

                            Swal.fire({
                                title: 'Processing...',
                                html: 'Please wait while we delete your todo list',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });

                            fetch('{{ route('employe.todolist.delete') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    id: id
                                })
                            }).then(response => {
                                return response.json();
                            }).then(data => {
                                Swal.close();

                                swalDelete.fire({
                                    title : "Deleted!",
                                    text : data.msg,
                                    icon: "success" 
                                });

                                $this.todolistData = data.data;
                            }).catch(error => {
                                Swal.close();
                                swalDelete.fire({
                                    title: "Error!",
                                    text: "Failed to delete todo list",
                                    icon: "error"
                                });
                                console.error('Error:', error);
                            });
                        } else {
                            result.dismiss == Swal.DismissReason.cancel
                        }
                    });
                },
                hanldePusher(){
                    var $this = this;
                    var pusher = new Pusher('c5cee67bd5404f589f4e', {
                        cluster: 'ap1'
                    });
                    var channel = pusher.subscribe('todo-comments');
                    channel.bind('comment-channel', function(data){
                        $this.allComments = data.comment;
                        $this.commentArr = $this.allComments[$this.commentForm.todolist_id];
                    });
                },
                init(){
                    const $this = this;
                    
                    $this.hanldePusher();

                    this.$nextTick(() => {

                        $this.intiallizeSortable('#status-1');
                        $this.intiallizeSortable('#status-2');
                        $this.intiallizeSortable('#status-3');
                        $this.intiallizeSortable('#status-4');

                        this.newQuillForm = new Quill('#quill-desc', {
                            theme: 'snow',
                            modules: { toolbar: true }
                        })

                        this.editQuillForm = new Quill('#edit-quill-desc', {
                            theme: 'snow',
                            modules: { toolbar: true }
                        });

                        this.newQuillForm.on('text-change', () => {
                            this.newForm.description = this.newQuillForm.root.innerHTML
                        });

                        this.editQuillForm.on('text-change', () => {
                            this.editForm.description = this.editQuillForm.root.innerHTML
                        })
                    })

                }
            }));
        })
    </script>
@endpush