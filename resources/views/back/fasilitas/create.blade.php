@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0">Tambah Fasilitas Hotel</h2>
    <p class="text-muted small mb-0">Masukkan informasi fasilitas baru.</p>
  </div>
  <a href="{{ route('fasilitas.index') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
</div>

{{-- Alert Bootstrap Notification --}}
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4" role="alert">
    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

@if($errors->any())
  <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4" role="alert">
    <i class="fas fa-exclamation-triangle me-2"></i><strong>Terjadi Kesalahan!</strong> Mohon periksa kembali inputan Anda.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
  <div class="card-body p-4">
    <form id="formFasilitas" action="{{ route('fasilitas.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Fasilitas</label>
        <input type="text" name="nama_fasilitas" class="form-control @error('nama_fasilitas') is-invalid @enderror" value="{{ old('nama_fasilitas') }}" placeholder="Contoh: Kolam Renang Outdoor" required>
        @error('nama_fasilitas')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Deskripsi</label>
        <textarea name="deskripsi" id="myeditor" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" placeholder="Penjelasan singkat mengenai fasilitas...">{{ old('deskripsi') }}</textarea>
        @error('deskripsi')
          <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Fasilitas</button>
    </form>
  </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

<script>
  var options = {
    filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
    filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token={{ csrf_token() }}',
    filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
    filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token={{ csrf_token() }}',
    clipboard_handleImages: false
  };

  if (document.getElementById('myeditor')) {
    CKEDITOR.replace('myeditor', options);
  }

  // Konfirmasi submit menggunakan confirm() bawaan browser
  $('#formFasilitas').on('submit', function(e) {
    if (CKEDITOR.instances.myeditor) {
      CKEDITOR.instances.myeditor.updateElement();
    }

    var yakin = confirm("Apakah Anda yakin ingin menyimpan fasilitas ini?");
    if (!yakin) {
      e.preventDefault();
    }
  });
</script>
@endpush