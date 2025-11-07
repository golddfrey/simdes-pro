@extends('layout')

@section('content')
  <!-- Hero -->
  <main>
    <section class="max-w-7xl mx-auto px-6 py-16 flex flex-col-reverse lg:flex-row items-center gap-12">
      <div class="w-full lg:w-1/2">
        <h2 class="text-3xl sm:text-4xl font-bold text-gray-900">Permudah Administrasi Desa dengan SimDes</h2>
        <p class="mt-4 text-gray-600">Kelola data penduduk, layanan publik, surat, dan laporan keuangan secara terpusat dan aman. Dirancang untuk perangkat desa yang ingin lebih efisien.</p>
        <div class="mt-6 flex flex-wrap gap-3">
          <a href="#daftar" class="px-5 py-3 bg-indigo-600 text-white rounded-md shadow hover:bg-indigo-700">Daftar Sekarang</a>
          <a href="#fitur" class="px-5 py-3 border border-indigo-600 text-indigo-600 rounded-md hover:bg-indigo-50">Pelajari Lebih Lanjut</a>
        </div>

        <div class="mt-8 grid grid-cols-2 gap-4 sm:grid-cols-3">
          <div class="bg-white rounded-lg p-4 shadow-sm">
            <p class="text-sm text-gray-500">Pengguna Terdaftar</p>
            <p class="mt-1 text-xl font-semibold">1.2k+</p>
          </div>
          <div class="bg-white rounded-lg p-4 shadow-sm">
            <p class="text-sm text-gray-500">Surat Dibuat</p>
            <p class="mt-1 text-xl font-semibold">8.4k</p>
          </div>
          <div class="bg-white rounded-lg p-4 shadow-sm">
            <p class="text-sm text-gray-500">Rata-rata Waktu</p>
            <p class="mt-1 text-xl font-semibold">2 menit</p>
          </div>
        </div>
      </div>

      <div class="w-full lg:w-1/2">
        <div class="relative">
          <div class="bg-gradient-to-tr from-indigo-500 to-indigo-300 rounded-2xl p-1 transform rotate-3 shadow-lg">
            <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?auto=format&q=60&fit=crop&w=800" alt="dashboard preview" class="rounded-xl w-full object-cover h-80 sm:h-96">
          </div>
          <div class="absolute -bottom-6 left-4 bg-white rounded-lg p-4 shadow-lg w-64">
            <p class="text-xs text-gray-500">Contoh notifikasi</p>
            <p class="mt-1 text-sm font-medium">Pengajuan KTP berhasil diproses</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Features -->
    <section id="fitur" class="bg-white py-12">
      <div class="max-w-7xl mx-auto px-6">
        <h3 class="text-2xl font-semibold">Fitur Unggulan</h3>
        <p class="mt-2 text-gray-600">Solusi lengkap untuk kebutuhan administrasi desa.</p>

        <div class="mt-8 grid gap-6 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3">
          <div class="p-6 bg-gray-50 rounded-lg border">
            <div class="flex items-center space-x-4">
              <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6M9 7h6" />
                </svg>
              </div>
              <div>
                <h4 class="font-semibold">Manajemen Penduduk</h4>
                <p class="text-sm text-gray-500 mt-1">Data keluarga, kependudukan, dan pencarian cepat.</p>
              </div>
            </div>
          </div>

          <div class="p-6 bg-gray-50 rounded-lg border">
            <div class="flex items-center space-x-4">
              <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-1.657 0-3 .895-3 2v3h6v-3c0-1.105-1.343-2-3-2zM6 18v1a1 1 0 001 1h10a1 1 0 001-1v-1" />
                </svg>
              </div>
              <div>
                <h4 class="font-semibold">Layanan Surat</h4>
                <p class="text-sm text-gray-500 mt-1">Buat dan kelola surat keterangan, pengantar, dan arsip.</p>
              </div>
            </div>
          </div>

          <div class="p-6 bg-gray-50 rounded-lg border">
            <div class="flex items-center space-x-4">
              <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 17a4 4 0 01-4-4V7a4 4 0 118 0v6a4 4 0 01-4 4z" />
                </svg>
              </div>
              <div>
                <h4 class="font-semibold">Laporan Keuangan</h4>
                <p class="text-sm text-gray-500 mt-1">Rekap anggaran, realisasi, dan transparansi publik.</p>
              </div>
            </div>
          </div>

          <div class="p-6 bg-gray-50 rounded-lg border">
            <div class="flex items-center space-x-4">
              <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v4a1 1 0 001 1h3m10 0h3a1 1 0 001-1V7M7 10V7a5 5 0 0110 0v3" />
                </svg>
              </div>
              <div>
                <h4 class="font-semibold">Keamanan & Backup</h4>
                <p class="text-sm text-gray-500 mt-1">Backup otomatis dan kontrol akses berbasis peran.</p>
              </div>
            </div>
          </div>

          <div class="p-6 bg-gray-50 rounded-lg border">
            <div class="flex items-center space-x-4">
              <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <h4 class="font-semibold">Integrasi</h4>
                <p class="text-sm text-gray-500 mt-1">API sederhana untuk integrasi dengan sistem pemerintahan lain.</p>
              </div>
            </div>
          </div>

          <div class="p-6 bg-gray-50 rounded-lg border">
            <div class="flex items-center space-x-4">
              <div class="p-3 bg-indigo-50 text-indigo-600 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7h18M3 12h18M3 17h18" />
                </svg>
              </div>
              <div>
                <h4 class="font-semibold">Pelaporan Publik</h4>
                <p class="text-sm text-gray-500 mt-1">Publikasikan berita dan pengumuman desa dengan mudah.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Contact / Footer -->
    <section id="kontak" class="bg-indigo-50 py-10">
      <div class="max-w-4xl mx-auto px-6">
        <div class="grid md:grid-cols-2 gap-6">
          <div>
            <h5 class="font-semibold">Kontak</h5>
            <p class="text-sm text-gray-600 mt-2">Butuh bantuan? Hubungi kami di <a href="mailto:support@simdes.example" class="text-indigo-600">support@simdes.example</a></p>
          </div>
          <div>
            <h5 class="font-semibold">Alamat</h5>
            <p class="text-sm text-gray-600 mt-2">Jl. Contoh No.123, Kecamatan, Kab.</p>
          </div>
        </div>

        <footer class="mt-8 text-center text-sm text-gray-500">
          <p>&copy; <span id="year"></span> SimDes. Semua hak dilindungi.</p>
        </footer>
      </div>
    </section>
  </main>
@endsection

@push('scripts')
  <script>
    // mobile menu toggle
    const menuBtn = document.getElementById('menuBtn')
    const mobileMenu = document.getElementById('mobileMenu')
    menuBtn?.addEventListener('click', () => {
      mobileMenu.classList.toggle('hidden')
    })

    // set year
    document.getElementById('year').textContent = new Date().getFullYear()
  </script>
@endpush
