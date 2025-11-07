@extends('layouts.windmill')

@section('title','Anggota Keluarga')

@section('content')
    <div class="max-w-4xl mx-auto px-6 py-10">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold">Anggota Keluarga</h1>
            <p class="text-gray-600 mt-1">Kelola daftar anggota keluarga Anda. Gunakan tombol di bawah untuk menambah anggota baru.</p>
        </div>

        <div class="flex justify-center mb-6">
            <a href="{{ route('kepala.anggota.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm hover:bg-indigo-700">Tambah Anggota</a>
        </div>

        @if(session('status'))
            <div class="mb-4 p-3 rounded bg-green-50 text-green-800 text-center">{{ session('status') }}</div>
        @endif

                <div class="flex justify-center">
                    <div class="w-full md:w-full lg:w-11/12 space-y-6">
                        @forelse($anggota as $a)
                            @php
                                $actions = '<a href="' . route('kepala.anggota.edit', $a->id) . '" class="inline-block px-3 py-2 bg-indigo-600 text-white rounded">Edit</a>';
                                if (empty($a->alamat)) {
                                    $a->alamat = $a->alamat ?? '-';
                                }
                            @endphp

                            <div>
                                @include('kepala.components.profile-card', ['person' => $a, 'actions' => $actions])
                            </div>

                        @empty
                            <div class="p-6 bg-white rounded shadow text-center text-gray-600">Tidak ada anggota keluarga.</div>
                        @endforelse
                    </div>
                </div>
    </div>
@endsection
