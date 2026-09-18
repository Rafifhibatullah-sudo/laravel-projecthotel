@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0"> <i class="fas fa-bed me-2"></i>Data Kamar</h2>
    <p class="text-muted small mb-0">Kelola daftar jenis, ketersediaan stok, dan spesifikasi kamar hotel.</p>
  </div>
  <a href="{{ route('kamar.create') }}" class="btn btn-primary rounded-3 shadow-sm">
    <i class="fas fa-plus me-1"></i> Tambah Kamar
  </a>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
  <div class="card-body p-4">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table-hover align-middle datatable w-100">
        <thead class="table-light">
          <tr>
            <th width="5%">No</th>
            <th width="10%">Foto</th>
            <th>Nama Kamar</th>
            <th>Tipe</th>
            <th>Harga / Malam</th>
            <th>Sisa Stok / Total</th>
            <th width="25%" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($kamars as $row)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
              @if($row->foto)
                <img src="{{ asset('storage/' . $row->foto) }}" class="rounded-3" width="70" height="50" style="object-fit: cover;" alt="{{ $row->nama_kamar }}">
              @else
                <span class="badge bg-secondary">No Photo</span>
              @endif
            </td>
            <td class="fw-semibold">{{ $row->nama_kamar }}</td>
            <td><span class="fw-bold text-dark">{{ $row->tipe_kamar }}</span></td>
            <td class="fw-bold">Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
            
            <!-- POIN 5: Tampilan Sisa Stok Real-Time -->
            <td>
              @php
                $totalStok = $row->jumlah_kamar ?? $row->stok ?? 0;
                $sisaStok = $row->sisa_stok ?? $totalStok;
              @endphp

              @if($sisaStok > 0)
                <span class="badge bg-success-subtle text-success border border-success px-2 py-1 rounded-2">
                  <i class="fas fa-check-circle me-1"></i> Sisa: {{ $sisaStok }} Unit
                </span>
              @else
                <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1 rounded-2">
                  <i class="fas fa-times-circle me-1"></i> Penuh / Habis
                </span>
              @endif
              <small class="d-block text-muted mt-1">Total: {{ $totalStok }} Unit</small>
            </td>

            <td class="text-center">
             <form action="{{ route('kamar.destroy', $row->id) }}" method="POST" class="d-inline-flex gap-1" onsubmit="return confirm('Yakin ingin menghapus kamar ini?');">
                @csrf
                @method('DELETE')
                
                <a href="{{ route('kamar.show', $row->id) }}" class="btn btn-sm btn-secondary text-white rounded-2 px-2">
                  <i class="fas fa-info me-1"></i>Detail
                </a>
                
                <a href="{{ route('kamar.edit', $row->id) }}" class="btn btn-sm btn-primary text-white rounded-2 px-2">
                  <i class="fas fa-edit me-1"></i>Edit
                </a>
                
                <button type="submit" class="btn btn-sm btn-danger rounded-2 px-2">
                  <i class="fas fa-trash me-1"></i>Hapus
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection