<div class="card h-100">
    <div class="card-header pb-0">
        <h6>Top 3 Employe</h6>
    </div>
    <div class="card-body h-100">
        @if (!empty($employes))
            @php
                $top1 = $employes[0] ?? null;
                $top2 = $employes[1] ?? null;
                $top3 = $employes[2] ?? null;
            @endphp

            {{-- Juara 1 (tengah) --}}
            @if ($top1)
                <div class="d-flex w-100 justify-content-center align-items-center">
                    <div class="flip-card">
                        <div class="flip-card-inner">
                            <div class="flip-card-front border border-3">
                                <img src="{{ get_data_image($top1['image'])['img_url'] }}" alt="{{ $top1['name'] }}" class="avatar-img">
                            </div>
                            <div class="flip-card-back">
                                <div class="fs-3 text-bold">1</div>
                                <div class="text-xs text-center">{{ $top1['name'] }}</div>
                                <div class="text-xs text-center opacity-7">{{ $top1['final_score'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Juara 2 dan 3 (kiri & kanan) --}}
            <div class="d-flex w-100 justify-content-evenly flex-wrap align-items-center">
                @if ($top2)
                    <div class="flip-card">
                        <div class="flip-card-inner">
                            <div class="flip-card-front border border-3">
                                <img src="{{ get_data_image($top2['image'])['img_url'] }}" alt="{{ $top2['name'] }}" class="avatar-img">
                            </div>
                            <div class="flip-card-back">
                                <div class="fs-3 text-bold">2</div>
                                <div class="text-xs text-center">{{ $top2['name'] }}</div>
                                <div class="text-xs text-center opacity-7">{{ $top2['final_score'] }}</div>
                            </div>
                        </div>
                    </div>
                @endif

                @if ($top3)
                    <div class="flip-card">
                        <div class="flip-card-inner">
                            <div class="flip-card-front border border-3">
                                <img src="{{ get_data_image($top3['image'])['img_url'] }}" alt="{{ $top3['name'] }}" class="avatar-img">
                            </div>
                            <div class="flip-card-back">
                                <div class="fs-3 text-bold">3</div>
                                <div class="text-xs text-center">{{ $top3['name'] }}</div>
                                <div class="text-xs text-center opacity-7">{{ $top3['final_score'] }}</div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

</div>