@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0">Tambah Fasilitas Hotel</h2>
    <p class="text-muted small mb-0">Masukkan informasi fasilitas baru.</p>
  </div>
  <a href="{{ route('fasilitas.index') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
  <div class="card-body p-4">
    <form action="{{ route('fasilitas.store') }}" method="POST">
      @csrf
      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Fasilitas</label>
        <input type="text" name="nama_fasilitas" class="form-control" placeholder="Contoh: Kolam Renang Outdoor" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Deskripsi</label>
        <textarea name="deskripsi" id="myeditor" class="form-control" rows="4" placeholder="Penjelasan singkat mengenai fasilitas..."></textarea>
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