@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0"><i class="fas fa-edit me-2"></i>Edit Fasilitas</h2>
    <p class="text-muted small mb-0">Ubah data fasilitas hotel.</p>
  </div>
  <a href="{{ route('fasilitas.index') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
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
    <form action="{{ route('fasilitas.update', $fasilitas->id) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label fw-semibold">Nama Fasilitas</label>
        <input type="text" name="nama_fasilitas" class="form-control" value="{{ old('nama_fasilitas', $fasilitas->nama_fasilitas) }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label fw-semibold">Deskripsi (Opsional)</label>
        <textarea name="deskripsi" id="myeditor" class="form-control" rows="4">{!! old('deskripsi', $fasilitas->deskripsi) !!}</textarea>
      </div>

      <button type="submit" class="btn btn-primary rounded-3 px-4">
        <i class="fas fa-save me-1"></i> Update Fasilitas
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