@extends('layout')

@section('content')
<h1>Ajukan Surat</h1>
<form method="post" action="{{ route('kepala.surat.store') }}">
    @csrf
    <label>Jenis Surat: <input name="jenis_surat" required></label><br>
    <label>Tujuan: <input name="tujuan"></label><br>
    <label>Keterangan: <textarea name="keterangan"></textarea></label><br>
    <button type="submit">Ajukan</button>
</form>
@endsection
