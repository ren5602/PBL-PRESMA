@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        {{-- background profile BG --}}
        <div class="profile-header"
            style="background: url('{{ asset('images/polinemas.png') }}') center/cover no-repeat; height: 200px;">
        </div>

        {{-- Foto Profil & nama nim --}}
        <div class="d-flex" style="margin-top: -75px;">
            <div class="d-flex align-items-end" style="margin-left: 50px; gap: 30px;">
                {{-- Foto Profil --}}
                <div style="width: 150px; height: 150px; z-index: 2; position: relative;">
                    <img src="{{ $detail->foto_profile ? asset('storage/' . $detail->foto_profile) : asset('images/default-profile2.jpg') }}"
                        alt="Foto Profil" class="rounded-circle"
                        style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                </div>

                {{-- nama lengkap --}}
                <div style="z-index: 2;">
                    <h4 class="mt-2">{{ $detail->nama_lengkap }}</h4>
                    <p class="text-muted mb-0">
                        {{ $user->role === 'mahasiswa' ? $detail->nim : ($user->role === 'dosen' ? $detail->nidn : $user->id) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- profile content --}}
        <div class="card" style="margin-top: -75px; z-index: 1;">
            <div class="card-body">

                {{-- edit profile --}}
                <div class="d-flex justify-content-end" style=" padding-right: 30px; padding-bottom: 40px;">
                    <a href="{{ route('profile.edit') }}" class="btn btn-dark btn-sm">
                        <i class="fas fa-edit"></i> Edit Profile
                    </a>
                </div>
                <hr>

                {{-- personal information --}}
                <div class="row text-left" style="margin-top: 10px; margin-left: 30px">
                    <h5><strong>Personal Information</strong> <i class="fa-solid fa-pencil fa-sm"></i></h5>
                </div>
                <div class="row text-left">
                    {{-- Baris 1 --}}
                    <div class="row" style="margin-right: 30px; margin-left: 30px">
                        {{-- Kolom 1 --}}
                        <div class="col" style="margin-right: 200px;">
                            @if ($user->role === 'mahasiswa')
                                <p><i class="fa-solid fa-book"></i> <strong>Program Studi:</strong>
                                    <br>{{ $detail->programStudi->nama_prodi ?? '-' }}
                                </p>
                                <p><i class="fa-solid fa-calendar-days"></i> <strong>Angkatan:</strong>
                                    <br>{{ $detail->angkatan }}
                                </p>
                            @elseif($user->role === 'dosen')
                                <p><i class="fa-solid fa-book"></i> <strong>Program Studi:</strong>
                                    <br>{{ $detail->programStudi->nama_prodi ?? '-' }}
                                </p>
                            @elseif($user->role === 'admin')
                                <p><i class="fa-solid fa-at"></i> <strong>Email:</strong> <br>{{ $user->email }}</p>
                            @endif
                        </div>
                        {{-- Kolom 2 --}}
                        <div class="col" style="margin-right: 200px;">
                            @if ($user->role === 'mahasiswa')
                                <p><i class="fa-solid fa-mobile-screen-button"></i> <strong>No. HP:</strong>
                                    {{ $detail->no_telp }}</p>
                                <p><i class="fa-solid fa-location-dot"></i> <strong>Alamat:</strong>
                                    <br>{{ $detail->alamat }}
                                </p>
                            @elseif($user->role === 'dosen')
                                <p><i class="fa-solid fa-mobile-screen-button"></i> <strong>No. HP:</strong>
                                    {{ $detail->no_telp }}</p>
                            @endif
                        </div>
                        {{-- Kolom 3 --}}
                        <div class="col" style="margin-right: 100px">
                            @if ($user->role === 'mahasiswa')
                                <p><i class="fa-solid fa-at"></i> <strong>Email:</strong> <br>{{ $user->email }}</p>
                            @elseif($user->role === 'dosen')
                                <p><i class="fa-solid fa-at"></i> <strong>Email:</strong> <br>{{ $user->email }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @if ($user->role === 'mahasiswa' && $detail->bidangKeahlian())
                    <hr>
                    <div class="row text-left" style="margin-top: 20px; margin-left: 30px">
                        <h5><strong>Keahlian</strong> <i class="fa-solid fa-star fa-sm"></i></h5>
                    </div>
                    <div class="row text-left">
                        <div class="row" style="margin-right: 30px; margin-left: 10px">
                            <div class="col"">
                                <ul>
                                    @foreach ($detail->bidangKeahlian as $item)
                                        <li>
                                            {{ $item->bidangKeahlian->keahlian ?? 'Keahlian tidak ditemukan' }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
                {{-- view pengalaman mahasiswa --}}
                @if ($user->role === 'mahasiswa' && $detail->pengalaman())
                <hr>
                    <div class="row text-left" style="margin-top: 20px; margin-left: 30px">
                        <h5><strong>Pengalaman</strong> <i class="fa-solid fa-pencil fa-sm"></i></h5>
                    </div>
                    <div class="row text-left">
                        <div class="row" style="margin-right: 30px; margin-left: 30px">
                            <div class="col" style="margin-right: 100px;">
                                @foreach ($detail->pengalaman as $pengalaman)
                                    <p>
                                        <i class="fa-solid fa-seedling"></i>
                                        <strong>{{ ucfirst($pengalaman->bidangKeahlian->keahlian ?? 'Kategori Tidak Ditemukan') }}:</strong><br>
                                        {{ $pengalaman->pengalaman }}
                                    </p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                {{-- view sertifikasi mahasiswa --}}
                @if ($user->role === 'mahasiswa' && $detail->sertifikasis && $detail->sertifikasis())
                    <hr>
                    <div class="row text-left" style="margin-top: 20px; margin-left: 30px">
                        <h5><strong>Sertifikasi</strong> <i class="fa-solid fa-award fa-sm"></i></h5>
                    </div>
                    <div class="row text-left">
                        <div class="row" style="margin-right: 30px; margin-left: 30px">
                            <div class="col" style="margin-right: 100px;">
                                @foreach ($detail->sertifikasis as $sertifikasi)
                                    <div class="mb-3">
                                        <p>
                                            <i class="fa-solid fa-certificate"></i>
                                            <strong>{{ $sertifikasi->judul }}</strong><br>
                                            <span class="text-muted">
                                                Bidang Keahlian:
                                                {{ $sertifikasi->bidangKeahlian->keahlian ?? 'Kategori Tidak Ditemukan' }}
                                            </span><br>
                                            @if ($sertifikasi->path)
                                                <a href="{{ asset('storage/' . $sertifikasi->path) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-primary mt-1">
                                                    <i class="fa-solid fa-file-pdf"></i> Lihat Sertifikat
                                                </a>
                                            @else
                                                <span class="text-danger">Tidak ada file sertifikat</span>
                                            @endif
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                {{-- view bidang minat dosen --}}
                @if ($user->role === 'dosen' && $detail->bidangMinat->isNotEmpty())
                    <hr>
                    <div class="row text-left" style="margin-top: 20px; margin-left: 30px">
                        <h5><strong>Bidang Minat</strong> <i class="fa-solid fa-flask fa-sm"></i></h5>
                    </div>
                    <div class="row text-left">
                        <div class="row" style="margin-right: 30px; margin-left: 10px">
                            <div class="col">
                                <ul>
                                    @foreach ($detail->bidangMinat as $minat)
                                        <li>{{ $minat->bidangMinat->bidang_minat ?? 'Bidang minat tidak ditemukan' }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
