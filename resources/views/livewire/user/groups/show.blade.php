<div>
    {{-- Header --}}
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>{{ $group->name }}</h4>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <a href="{{ route('user.dashboard') }}" class="btn btn-primary" wire:navigate>Kembali</a>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="card-title">Deskripsi:</div>
                    <p class="mb-0">{{ $group->description }}</p>
                </div>
            </div>
        </div>
    </div>
    {{-- Header --}}

    {{-- Navtabs --}}
    <div class="card">
        <div class="card-body">
            <div class="custom-tab-1">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#home1">
                            <i class="la la-user me-2"></i>
                            Anggota</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#profile1">
                            <i class="la la-book me-2"></i>
                            Instrumen</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="home1" role="tabpanel">
                        <div class="pt-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Daftar anggota</h4>
                                </div>
                                <div class="card-body">
                                    @forelse ($members as $item)
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $item->avatar ? asset('storage/' . $item->avatar) : asset('assets/images/avatar/default-avatar.jpg') }}"
                                                alt="user-avatar"
                                                style="width: 50px; height: 50px; object-fit: cover ;object-fit: cover"
                                                class="rounded-2">
                                            <h5>
                                                {{ $item->name }}
                                            </h5>
                                        </div>
                                        <hr>
                                    @empty
                                        <h5>
                                            Tidak ada anggota
                                        </h5>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile1">
                        <div class="pt-4">
                            <div class="card-header">
                                <h4 class="card-title">Daftar Instrumen</h4>
                            </div>
                            <div class="card-body">
                                @forelse ($instruments as $item)
                                    <div class="row">
                                        <div class="d-sm-block d-lg-flex d-md-flex align-items-center">
                                            <div class="col-xl-9 col-md-9 col-sm-12">
                                                <h5>
                                                    {{ $item->title }}
                                                </h5>
                                            </div>
                                            <div class="col-xl-3 col-md-3 col-sm-12">
                                                <div class="d-flex gap-2">
                                                    <a wire:navigate
                                                        href="{{ route('pengerjaan.index', ['group_id' => $group->id, 'instrumen_id' => $item->id]) }}"
                                                        class="btn btn-primary">Kerjakan</a>
                                                    <a wire:navigate
                                                        href="{{ route('user-instruments.show', ['user_instrument' => encrypt($item->id)]) }}"
                                                        class="btn btn-warning">Detail</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                @empty
                                    <h5>
                                        Tidak ada Instrumen
                                    </h5>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Navtabs --}}
</div>
