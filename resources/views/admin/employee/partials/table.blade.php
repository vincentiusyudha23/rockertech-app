<div class="table-responsive p-0">
    <table class="table align-items-center mb-0" id="table-employee">
        <thead>
            <tr>
                <th class="text-uppercase text-secondary text-center text-xxs font-weight-bolder opacity-7">Card ID</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Position</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Username</th>
                <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Password</th>
                <th class="text-secondary opacity-7"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($employees as $employ)
                <tr>
                    <td class="align-middle text-center">
                        <span class="text-secondary text-sm font-weight-bold" id="card_id-{{ $employ->id }}">{{ $employ->card_id }}</span>
                    </td>
                    <td>
                        <div class="d-flex px-2 py-1">
                            <div>
                                @php
                                    $image = get_data_image($employ?->image);
                                @endphp
                                <img src="{{ $image['img_url'] ?? '' }}" class="avatar avatar-md me-3" alt="{{ $image['alt'] ?? '' }}">
                            </div>
                            <div class="d-flex flex-column justify-content-center">
                                <h6 class="mb-0 text-sm">{{ $employ->name }}</h6>
                                <p class="text-xs text-secondary mb-0">{{ $employ->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p class="text-xs font-weight-bold mb-0">STAF</p>
                        <p class="text-xs text-secondary mb-0">{{ $employ->position }}</p>
                    </td>
                    <td class="align-middle text-center text-sm">
                        <p class="text-sm text-secondary mb-0">{{ $employ->user->username }}</p>
                    </td>
                    <td class="align-center text-center show-password">
                        <span class="text-secondary text-xs font-weight-bold  active">{{ decryptPassword($employ->enc_password) }}</span>
                        <i class="fa-solid fa-eye mx-1"></i>
                    </td>
                    <td class="align-middle">
                        <a href="javascript:;" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip"
                            data-original-title="Edit user">
                            Edit
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
