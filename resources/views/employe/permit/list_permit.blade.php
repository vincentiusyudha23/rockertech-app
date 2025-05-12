@extends('layouts.master')

@section('title', 'List Permit')

@section('content')
    <x-navbar title_page="List Permit">
        <div class="w-100 p-2" >
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0" id="permit-table">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">No.</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
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
                                        <td class="text-center">{{ $permit->employe->name }}</td>
                                        <td class="text-center">{{ $permit->from_date->format('d-m-Y') }}</td>
                                        <td class="text-center">{{ $permit->to_date->format('d-m-Y') }}</td>
                                        <td class="text-center">{!! \App\Enums\PermitTypeEnum::from($permit->type)->badgeType() !!}</td>
                                        <td class="text-center">{!! \App\Enums\PermitStatusEnum::from($permit->status)->badgeStatus() !!}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                <x-permit-modal-edit :permit="$permit"/>
                                                <form action="{{ route('employe.permit.delete', ['id' => $permit->id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-icon btn-sm bg-gradient-danger m-0 px-3 py-2">
                                                        <i class="fa-solid fa-trash text-sm"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </x-navbar>
@endsection

@push('scripts')
    @if (session('success'))
        <script>
            toastr.success('{{ session('success') }}');
        </script>
    @endif

    @if (session('errors'))
        <script>
            toastr.error('{{ session('errors') }}');
        </script>
    @endif

    <script>
        $(document).ready(function(){
            $('#permit-table').DataTable({
                scrollX: true,
                responsive: false,
            });

            $('.edit-reason').each(function(index, el){
                const quill = new Quill('#' + $(el).data('id'), {
                    theme: 'snow',
                    modules: { toolbar: true }
                });

                quill.root.innerHTML = $(el).data('val');

                let InputReasonTag = $(el).next();
                let btnSave = $(el).data('button');
                let editForm = $(el).data('form');

                quill.on('text-change', function(){
                    InputReasonTag.val(quill.root.innerHTML);
                });

                $(editForm).on('submit', function(e){
                    $(btnSave).addClass('disabled');
                    $(btnSave).html('<i class="fa-solid fa-spinner fa-spin text-sm"></i>');
                    return true;
                });
            });

        });
    </script>
@endpush