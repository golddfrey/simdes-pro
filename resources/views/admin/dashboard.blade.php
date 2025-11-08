

@section('content')
<div class="max-w-6xl mx-auto px-6 py-8">
</* cards and charts */>
  <h1 class="text-2xl font-semibold mb-4">Admin Dashboard</h1>

  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
    <div class="p-4 bg-white rounded shadow flex items-center">
      <div class="p-3 bg-indigo-100 text-indigo-600 rounded-full mr-4">
        <!-- user/group icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m0-4a4 4 0 11-8 0 4 4 0 018 0zm8 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
      </div>
      <div>
        <p class="text-sm text-gray-500">Jumlah Kepala Keluarga</p>
        <p class="text-2xl font-bold">{{ $jumlahKepala ?? 0 }}</p>
      </div>
    </div>

    <div class="p-4 bg-white rounded shadow flex items-center">
      <div class="p-3 bg-green-100 text-green-600 rounded-full mr-4">
        <!-- people icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8a4 4 0 118 0M5.5 21a6.5 6.5 0 0113 0" /></svg>
      </div>
      <div>
        <p class="text-sm text-gray-500">Jumlah Total Penduduk</p>
        <p class="text-2xl font-bold">{{ $jumlahPenduduk ?? 0 }}</p>
      </div>
    </div>

    <a href="{{ route('admin.anggota.requests.index') }}" class="p-4 bg-white rounded shadow flex items-center">
      <div class="p-3 bg-yellow-100 text-yellow-600 rounded-full mr-4">
        <!-- clock/pending icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
      </div>
      <div>
        <p class="text-sm text-gray-500">Pending Pengajuan Anggota</p>
        <p class="text-2xl font-bold text-yellow-600">{{ $pendingPengajuan ?? 0 }}</p>
      </div>
    </a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
    <div class="p-4 bg-white rounded shadow flex items-center">
      <div class="p-3 bg-blue-100 text-blue-600 rounded-full mr-4">
        <!-- calendar icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
      </div>
      <div>
        <p class="text-sm text-gray-500">Kepala Keluarga Baru (periode 7 hari)</p>
        <p class="text-2xl font-bold">{{ $recentKepala->count() ?? 0 }}</p>
      </div>
    </div>

    <div class="p-4 bg-white rounded shadow flex items-center">
      <div class="p-3 bg-pink-100 text-pink-600 rounded-full mr-4">
        <!-- user-add icon -->
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9a3 3 0 11-6 0 3 3 0 016 0zm-9 9a6 6 0 0112 0H3zM6 3v6" /></svg>
      </div>
      <div>
        <p class="text-sm text-gray-500">Anggota Keluarga Baru (periode 7 hari)</p>
        <p class="text-2xl font-bold">{{ $recentAnggota->count() ?? 0 }}</p>
      </div>
    </div>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <div class="p-4 bg-white rounded shadow">
      <p class="text-sm text-gray-500">Chart: Distribusi Usia</p>
      <canvas id="ageChart" class="mt-3"></canvas>
    </div>

    <div class="p-4 bg-white rounded shadow">
      <p class="text-sm text-gray-500">Chart: Jenis Kelamin</p>
      <canvas id="genderChart" class="mt-3"></canvas>
    </div>
  </div>
</div>
@endsection
@extends('layouts.windmill')

{{-- @section('title','Admin Dashboard') --}}

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      // Age chart
      const ageLabels = {!! json_encode(array_keys($ageBuckets ?? [])) !!};
      const ageData = {!! json_encode(array_values($ageBuckets ?? [])) !!};
      const ctxAge = document.getElementById('ageChart').getContext('2d');
      new Chart(ctxAge, {
        type: 'bar',
        data: {
          labels: ageLabels,
          datasets: [{ label: 'Jumlah', data: ageData, backgroundColor: '#4f46e5' }]
        },
        options: { responsive: true }
      });

      // Gender chart
      const genderLabels = {!! json_encode(array_keys($genders ?? [])) !!};
      const genderData = {!! json_encode(array_values($genders ?? [])) !!};
      const ctxGender = document.getElementById('genderChart').getContext('2d');
      new Chart(ctxGender, {
        type: 'pie',
        data: { labels: genderLabels, datasets: [{ data: genderData, backgroundColor: ['#3b82f6','#ec4899','#9ca3af'] }] },
        options: { responsive: true }
      });
    });
  </script>
@endpush