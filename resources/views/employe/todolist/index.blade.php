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
        .sortable{
            min-height: 75%;
        }
    </style>
@endpush

@section('content')
    <x-navbar title_page="To-do List">
        <div class="d-flex gap-3 overflow-x-scroll hide-scrollbar px-2 todolist-parent" x-data="todoList">
            <template x-for="(day, index) in daysArr" :key="index">
                <div class="card todolist-card border-1 position-relative">
                    <div class="card-header pt-3 pb-0 m-0">
                        <div class="text-center">
                            <span class="text-bold fs-5" x-text="day"></span>
                        </div>
                    </div>
                    <div class="card-body list-todolist hide-scrollbar">
                        <ul :id="'day-'+index" class="sortable list-unstyled d-flex flex-column gap-3">
                            <li class="ui-state-default item-sortable border rounded-3 shadow-sm bg-white">

                                <div class="p-2">
                                    <div class="w-100 bg-gradient-danger rounded text-center py-1 ">
                                        <span class="text-white text-bold">High</span>
                                    </div>
                                </div>

                                <div class="p-3 pb-0">
                                    <blockquote class="blockquote mb-1">
                                        <p class="fs-6 ps-2">
                                            {{ Str::limit('Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...', 20, '...') }}
                                        </p>
                                    </blockquote>
    
                                    <div class="mb-1">
                                        <span class="fs-13px fw-bold">Due Date : 28/04/2025</span>
        
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold fs-13px">Assigned To:</span>
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Yudha" data-container="body" data-animation="true">
                                                    <img alt="image" src="{{ assets('img/team-2.jpg') }}">
                                                </a>
                                            </div>
    
                                            <span class="badge badge-pill bg-gradient-warning" style="scale: 0.85;">Pending</span>
                                        </div>
                                    </div>
    
                                    <div class="d-flex justify-content-between align-items-center p-0 m-0">
                                        <div>
                                            <i class="fa-regular fa-comment fa-xl"></i>
                                            <small>1</small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary mt-2" type="button">
                                            view
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <li class="ui-state-default item-sortable border rounded-3 shadow-sm bg-white">

                                <div class="p-2">
                                    <div class="w-100 bg-gradient-success rounded text-center py-1 ">
                                        <span class="text-white text-bold">Normal</span>
                                    </div>
                                </div>

                                <div class="p-3 pb-0">
                                    <blockquote class="blockquote mb-1">
                                        <p class="fs-6 ps-2">
                                            {{ Str::limit('Neque porro quisquam est qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit...', 20, '...') }}
                                        </p>
                                    </blockquote>
    
                                    <div class="mb-1">
                                        <span class="fs-13px fw-bold">Due Date : 28/04/2025</span>
        
                                        <div class="d-flex justify-content-between align-items-center gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="fw-bold fs-13px">Assigned To:</span>
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Yudha" data-container="body" data-animation="true">
                                                    <img alt="image" src="{{ assets('img/team-2.jpg') }}">
                                                </a>
                                            </div>
    
                                            <span class="badge badge-pill bg-gradient-warning" style="scale: 0.85;">Pending</span>
                                        </div>
                                    </div>
    
                                    <div class="d-flex justify-content-between align-items-center p-0 m-0">
                                        <div>
                                            <i class="fa-regular fa-comment fa-xl"></i>
                                            <small>1</small>
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary mt-2" type="button">
                                            view
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="w-100 position-absolute bottom-0 px-2">
                        <button class="btn btn-sm bg-gradient-info w-100" x-on:click="daySelect = day" data-bs-toggle="modal" data-bs-target="#modal-new-todolist">
                            Add To-do List
                        </button>
                    </div>
                </div>
            </template>

            <div class="modal fade" id="modal-new-todolist" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title font-weight-normal">Add New To-do List in <span x-text="daySelect"></span></h5>
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

                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="priority">Priority<sup class="text-danger">*</sup></label>
                                        <div class="input-group">
                                            <select class="form-select" x-model="newForm.priority" id="priority" aria-describedby="basic-addon3">
                                                <option selected>Select Priority</option>
                                                <option value="1">Low</option>
                                                <option value="2">Normal</option>
                                                <option value="3">High</option>
                                            </select>
                                        </div>    
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label" for="due-date">Due Date<sup class="text-danger">*</sup></label>
                                        <div class="input-group">
                                            <input type="date" id="due-date" class="form-control" x-model="newForm.dueDate">
                                        </div>
                                    </div>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <label class="form-control-label" for="quill-desc">Description</label>
                                <div>
                                    <div id="quill-desc" style="height: 150px;"></div>
                                </div>
                            </div>

                            <div class="w-100 mt-3">
                                <button type="button" class="btn btn-sm bg-gradient-info w-100">
                                    <span class="fs-6">Save</span>
                                </button>
                            </div>
                        </di>
                    </div>
                </div>
            </div>
        </div>

    </x-navbar>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script>
        const days = ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
        const newForm = [
            'title',
            'description',
            'priority',
            'dueDate'
        ];

        document.addEventListener('alpine:init', () => {
            Alpine.data('todoList', () => ({
                daysArr: days,
                newForm: newForm,
                daySelect: null,
                init(){

                    this.$nextTick(() => {
                        this.daysArr.forEach((_, dayIndex) => {
                            $(`#day-${dayIndex}`).sortable({
                                connectWith: '.sortable',
                                placeholder: "ui-state-highlight"
                            })
                        }).disableSelection();

                    })

                    this.quill = new Quill('#quill-desc', {
                        theme: 'snow',
                        modules: { toolbar: true }
                    })
                }
            }));
        })
    </script>
@endpush