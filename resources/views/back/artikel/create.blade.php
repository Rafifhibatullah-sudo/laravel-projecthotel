@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0"><i class="fas fa-pen me-2"></i>Tambah Artikel Baru</h2>
    <p class="text-muted small mb-0">Isi form di bawah untuk membuat artikel baru.</p>
  </div>
  <a href="{{ route('artikel.index') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
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
    <form id="formArtikel" action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col-md-8 mb-3">
          <label class="form-label fw-semibold">Judul Artikel</label>
          <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror" value="{{ old('judul') }}" placeholder="Contoh: Promo Special Diskon Akhir Tahun" required>
          @error('judul')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label fw-semibold">Kategori</label>
          <select name="kategori" class="form-select @error('kategori') is-invalid @enderror" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Promo" {{ old('kategori') == 'Promo' ? 'selected' : '' }}>Promo & Diskon</option>
            <option value="Berita Hotel" {{ old('kategori') == 'Berita Hotel' ? 'selected' : '' }}>Berita Hotel</option>
            <option value="Tips & Wisata" {{ old('kategori') == 'Tips & Wisata' ? 'selected' : '' }}>Tips & Wisata</option>
            <option value="Event" {{ old('kategori') == 'Event' ? 'selected' : '' }}>Event</option>
          </select>
          @error('kategori')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Gambar Header</label>
          <input type="file" name="gambar" id="img" class="form-control @error('gambar') is-invalid @enderror" accept="image/*" required>
          <small class="text-muted d-block">Format: JPG, PNG, WEBP. Max 2MB.</small>
          @error('gambar')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <img class="img-preview img-fluid mt-2 rounded-3" style="max-height: 150px; display: none;">
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Status Publikasi</label>
          <select name="status" class="form-select @error('status') is-invalid @enderror" required>
            <option value="publish" {{ old('status') == 'publish' ? 'selected' : '' }}>Publish</option>
            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
          </select>
          @error('status')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Isi Artikel</label>
          <textarea name="isi" id="myeditor" class="form-control @error('isi') is-invalid @enderror" rows="8" placeholder="Tuliskan isi artikel selengkapnya...">{{ old('isi') }}</textarea>
          @error('isi')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <button type="submit" class="btn btn-primary rounded-3 px-4">
        <i class="fas fa-paper-plane me-1"></i> Simpan Artikel
      </button>
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

  $("#img").change(function() {
    previewImage(this);
  });

  function previewImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('.img-preview').attr('src', e.target.result).show();
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  // Konfirmasi submit menggunakan confirm() bawaan browser
  $('#formArtikel').on('submit', function(e) {
    if (CKEDITOR.instances.myeditor) {
      CKEDITOR.instances.myeditor.updateElement();
    }

    var yakin = confirm("Apakah Anda yakin ingin menyimpan artikel ini?");
    if (!yakin) {
      e.preventDefault();
    }
  });
</script>
@endpush