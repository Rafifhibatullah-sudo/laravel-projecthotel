@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0"><i class="fas fa-newspaper me-2"></i>Kelola Artikel & Berita</h2>
    <p class="text-muted small mb-0">Kelola postingan promo, berita, dan tips wisata hotel.</p>
  </div>
  <a href="{{ route('artikel.create') }}" class="btn btn-primary rounded-3 shadow-sm">
    <i class="fas fa-plus me-1"></i> Tambah Artikel
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
      <table class=" table table-striped table-bordered table table-hover align-middle datatable w-100">
        <thead class="table-light">
          <tr>
            <th width="5%">No</th>
            <th width="12%">Gambar</th>
            <th>Judul Artikel</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Dilihat</th>
            <th width="25%" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($artikels as $row)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
              @if($row->gambar)
                <img src="{{ asset('storage/' . $row->gambar) }}" class="rounded-3" width="60" height="45" style="object-fit: cover;">
              @else
                <span class="badge bg-secondary">No Photo</span>
              @endif
            </td>
            <td class="fw-semibold">{{ $row->judul }}</td>
            <td><span class="badge bg-info-subtle text-dark border border-info-subtle px-3 py-1 rounded-pill">{{ $row->kategori }}</span></td>
            <td>
              @if($row->status == 'publish')
                <span class="badge bg-success">Publish</span>
              @else
                <span class="badge bg-danger text-dark">Private</span>
              @endif
            </td>
            <td><i class="fas fa-eye me-1 text-muted"></i>{{ $row->views }} x</td>
            <td class="text-center">
              <form action="{{ route('artikel.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                @csrf
                @method('DELETE')
                <a href="{{ route('artikel.show', $row->id) }}" class="btn btn-sm btn-secondary text-white rounded-2 px-2">
                  <i class="fas fa-info me-1"></i>Detail
                </a>
                
                <a href="{{ route('artikel.edit', $row->id) }}" class="btn btn-sm btn-primary text-white rounded-2 me-1">
                  <i class="fas fa-edit me-1"></i>Edit
                </a>


                <button type="submit" class="btn btn-sm btn-danger rounded-2">
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