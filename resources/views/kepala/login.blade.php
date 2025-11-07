<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login Kepala Keluarga</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-50 text-gray-800">
  <div class="max-w-md mx-auto mt-20">
    <div class="bg-white p-6 rounded shadow">
      <h1 class="text-lg font-semibold mb-4">Login Kepala Keluarga</h1>

      @if($errors->any())
        <div class="mb-3 text-red-600">{{ $errors->first() }}</div>
      @endif

      <form action="{{ route('kepala.login.post') }}" method="POST">
        @csrf
        <label class="block text-sm">NIK</label>
        <input type="text" name="nik" value="{{ old('nik') }}" class="w-full border rounded px-3 py-2 mb-4" required>
        <div class="flex justify-between items-center">
          <button class="px-4 py-2 bg-indigo-600 text-white rounded">Masuk</button>
          <a href="{{ route('home') }}" class="text-sm text-gray-600">Kembali</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>