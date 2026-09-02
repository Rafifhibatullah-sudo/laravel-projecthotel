@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0"><i class="fas fa-hotel me-2"></i>
       Fasilitas Hotel</h2>
    <p class="text-muted small mb-0">Kelola daftar fasilitas yang tersedia untuk tamu hotel.</p>
  </div>
  <a href="{{ route('fasilitas.create') }}" class="btn btn-primary rounded-3 shadow-sm">
    <i class="fas fa-plus me-1"></i> Tambah Fasilitas
  </a>
</div>

<div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
  <div class="card-body p-4">
    <div class="table-responsive">
      <!-- Tambahkan class 'datatable' di <table> -->
      <table class=" table table-striped table-bordered table table-hover align-middle datatable w-100">
        <thead class="table-light">
          <tr>
            <th width="5%">No</th>
            <th>Nama Fasilitas</th>
            <th>Deskripsi</th>
            <th width="25%" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($fasilitas as $row)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td class="fw-semibold">{{ $row->nama_fasilitas }}</td>
            <td>{!! $row->deskripsi !!}</td>
            <td class="text-center">
              <form action="{{ route('fasilitas.destroy', $row->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus fasilitas ini?');">
                @csrf
                @method('DELETE')
                
                <a href="{{ route('fasilitas.show', $row->id) }}" class="btn btn-sm btn-secondary text-white rounded-2 px-2">
                  <i class="fas fa-info me-1"></i>Detail
                </a>
                
                <a href="{{ route('fasilitas.edit', $row->id) }}" class="btn btn-sm btn-primary text-white rounded-2 px-2">
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