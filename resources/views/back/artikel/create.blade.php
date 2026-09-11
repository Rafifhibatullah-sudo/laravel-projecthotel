@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0"><i class="fas fa-pen me-2"></i>Tambah Artikel Baru</h2>
    <p class="text-muted small mb-0">Isi form di bawah untuk membuat artikel baru.</p>
  </div>
  <a href="{{ route('artikel.index') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
  <div class="card-body p-4">
    <form action="{{ route('artikel.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col-md-8 mb-3">
          <label class="form-label fw-semibold">Judul Artikel</label>
          <input type="text" name="judul" class="form-control" placeholder="Contoh: Promo Special Diskon Akhir Tahun" required>
        </div>

        
        <div class="col-md-4 mb-3">
          <label class="form-label fw-semibold">Kategori</label>
          <select name="kategori" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Promo">Promo & Diskon</option>
            <option value="Berita Hotel">Berita Hotel</option>
            <option value="Tips & Wisata">Tips & Wisata</option>
            <option value="Event">Event</option>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Gambar Header</label>
          <!-- Ditambahkan id="img" -->
          <input type="file" name="gambar" id="img" class="form-control" accept="image/*">
          <small class="text-muted d-block">Format: JPG, PNG, WEBP. Max 2MB.</small>
          <!-- Container Preview Gambar -->
          <img class="img-preview img-fluid mt-2 rounded-3" style="max-height: 150px;">
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Status Publikasi</label>
          <select name="status" class="form-select" required>
            <option value="publish">Publish</option>
            <option value="draft">draft</option>
          </select>
        </div>

        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Isi Artikel</label>
          <textarea name="isi" id="myeditor" class="form-control" rows="8" placeholder="Tuliskan isi artikel selengkapnya..."></textarea>
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

  CKEDITOR.replace('myeditor', options);

  $("#img").change(function() {
    previewImage(this);
  });

  function previewImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('.img-preview').attr('src', e.target.result);
      }
      reader.readAsDataURL(input.files[0]);
    }
  }
</script>
@endpush