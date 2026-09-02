@extends('back.layout.template')

@section('title', 'Detail Fasilitas - Admin')

@section('content')
<main class="w-100 px-md-4 mb-5">
    <!-- Header Section -->
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom" data-aos="fade-down">
        <h1 class="h2">Detail Fasilitas: {{ $fasilitas->nama_fasilitas }}</h1>
        
    </div>

    <!-- Main Content Table -->
    <div class="mt-3">
        <table class="table table-striped table-bordered align-middle" data-aos="fade-up" data-aos-delay="100">
            <tr data-aos="fade-right" data-aos-delay="150">
                <th width="250px">Nama Fasilitas</th>
                <td>: {{ $fasilitas->nama_fasilitas }}</td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="200">
                <th>Deskripsi</th>
                <td width="50%">: {!! $fasilitas->deskripsi !!}</td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="250">
                <th>Tanggal Dibuat</th>
                <td>: {{ $fasilitas->created_at ? $fasilitas->created_at->format('d M Y, H:i') . ' WIB' : '-' }}</td>
            </tr>
            <tr data-aos="fade-right" data-aos-delay="300">
                <th>Tanggal Diperbarui</th>
                <td>: {{ $fasilitas->updated_at ? $fasilitas->updated_at->format('d M Y, H:i') . ' WIB' : '-' }}</td>
            </tr>
        </table>

        <!-- Button Section -->
        <div class="float-end" data-aos="fade-up" data-aos-delay="350">
            <a href="{{ route('fasilitas.index') }}" class="btn btn-secondary">Kembali ke Daftar Fasilitas</a>
        </div>
    </div>
</main>
@endsection