<div class="table-responsive p-0">
    <table class="table align-items-center mb-0" id="table-employee">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Position</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Username</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Password</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @if (!empty($employees))
                @foreach ($employees as $employ)
                    <tr>
                        <td>
                            <div class="d-flex px-2 py-1">
                                <a>
                                    @php
                                        $image = get_data_image($employ?->image);
                                    @endphp
                                    <img src="{{ $image['img_url'] ?? '' }}" class="me-3 avatar avatar-md avatar-scale-up" alt="{{ $image['alt'] ?? '' }}">
                                </a>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="mb-0 text-sm">{{ $employ->name }}</h6>
                                    <p class="text-xs text-secondary mb-0">{{ $employ->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            <p class="text-xs text-secondary mb-0">{{ labelPosition($employ->position) }}</p>
                        </td>
                        <td class="align-middle text-center text-sm">
                            <p class="text-sm text-secondary mb-0">{{ $employ->user->username }}</p>
                        </td>
                        <td class="align-middle  text-center">
                            <div class="w-100 position-relative">
                                <div class="password active text-center position-absolute top-50 start-50 translate-middle z-1">
                                    <span class="text-secondary text-xs font-weight-bold">{{ decryptPassword($employ->enc_password) }}</span>
                                </div>
                                <div class="eye-password text-center position-absolute top-50 start-50 translate-middle z-2">
                                    <i class="fa-solid fa-eye text-sm"></i>
                                </div>
                            </div>
                        </td>
                        <td class="text-center">
                            {{-- <x-modal-edit :employe="$employ"/> --}}
                            <a href="{{ route('admin.employee.edit', ['id' => $employ->id]) }}" 
                                class="btn btn-icon bg-gradient-info m-0 px-3 py-2">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.employee.delete', ['id' => $employ->id]) }}"
                                class="btn btn-icon bg-gradient-danger m-0 px-3 py-2">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
