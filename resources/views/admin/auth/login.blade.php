@extends('layout')

@section('content')
<div class="max-w-md mx-auto mt-16">
    <div class="bg-white shadow-md rounded px-8 py-6">
        <h2 class="text-xl font-semibold mb-4">Login Admin</h2>

        @if($errors->any())
            <div class="mb-4 text-sm text-red-600">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full rounded-md border border-gray-200 py-2 px-3" />

            <label class="block text-sm font-medium text-gray-700 mt-4">Password</label>
            <input type="password" name="password" required class="mt-1 block w-full rounded-md border border-gray-200 py-2 px-3" />

            <div class="flex items-center mt-4">
                <input type="checkbox" name="remember" id="remember" class="mr-2" />
                <label for="remember" class="text-sm text-gray-600">Ingat saya</label>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded">Login sebagai Admin</button>
            </div>
        </form>
    </div>
</div>
@endsection
