@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0"><i class="fas fa-edit me-2"></i>Edit Artikel</h2>
    <p class="text-muted small mb-0">Ubah postingan artikel atau berita hotel.</p>
  </div>
  <a href="{{ route('artikel.index') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
</div>

@if ($errors->any())
  <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
    <ul class="mb-0">
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
  <div class="card-body p-4">
    <form action="{{ route('artikel.update', $artikel->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="col-md-8 mb-3">
          <label class="form-label fw-semibold">Judul Artikel</label>
          <input type="text" name="judul" class="form-control" value="{{ old('judul', $artikel->judul) }}" required>
        </div>

        <div class="col-md-4 mb-3">
          <label class="form-label fw-semibold">Kategori</label>
          <select name="kategori" class="form-select" required>
            <option value="Promo" {{ $artikel->kategori == 'Promo' ? 'selected' : '' }}>Promo & Diskon</option>
            <option value="Berita Hotel" {{ $artikel->kategori == 'Berita Hotel' ? 'selected' : '' }}>Berita Hotel</option>
            <option value="Tips & Wisata" {{ $artikel->kategori == 'Tips & Wisata' ? 'selected' : '' }}>Tips & Wisata</option>
            <option value="Event" {{ $artikel->kategori == 'Event' ? 'selected' : '' }}>Event</option>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Gambar Header </label>
          <input type="file" name="gambar" class="form-control" accept="image/*">
          @if($artikel->gambar)
            <div class="mt-2">
              <small class="text-muted d-block mb-1">Gambar saat ini:</small>
              <img src="{{ asset('storage/' . $artikel->gambar) }}" width="120" class="rounded-3 shadow-sm">
            </div>
          @endif
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Status Publikasi</label>
          <select name="status" class="form-select" required>
            <option value="publish" {{ $artikel->status == 'publish' ? 'selected' : '' }}>Publish (Langsung Tampil)</option>
            <option value="draft" {{ $artikel->status == 'draft' ? 'selected' : '' }}>Draft (Simpan Dulu)</option>
          </select>
        </div>

        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Isi Artikel</label>
          <textarea name="isi" id="myeditor" class="form-control" rows="8" required>{{ old('isi', $artikel->isi) }}</textarea>
        </div>
      </div>

      <button type="submit" class="btn btn-primary rounded-3 px-4">
        <i class="fas fa-save me-1"></i> Update Artikel
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