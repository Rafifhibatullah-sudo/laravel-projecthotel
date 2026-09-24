@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0">Tambah Kamar Baru</h2>
    <p class="text-muted small mb-0">Isi formulir berikut untuk memasukkan data kamar beserta fasilitasnya.</p>
  </div>
  <a href="{{ route('kamar.index') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
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
    <form id="formKamar" action="{{ route('kamar.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Nama Kamar</label>
          <input type="text" name="nama_kamar" class="form-control @error('nama_kamar') is-invalid @enderror" value="{{ old('nama_kamar') }}" placeholder="Contoh: Deluxe Room" required>
          @error('nama_kamar')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Tipe Kamar</label>
          <select name="tipe_kamar" class="form-select @error('tipe_kamar') is-invalid @enderror" required>
            <option value="" hidden>-- Pilih Tipe --</option>
            <option value="Single" {{ old('tipe_kamar') == 'Single' ? 'selected' : '' }}>Single Room</option>
            <option value="Double" {{ old('tipe_kamar') == 'Double' ? 'selected' : '' }}>Double Room</option>
            <option value="Deluxe" {{ old('tipe_kamar') == 'Deluxe' ? 'selected' : '' }}>Deluxe Room</option>
            <option value="Suite" {{ old('tipe_kamar') == 'Suite' ? 'selected' : '' }}>Suite Room</option>
          </select>
          @error('tipe_kamar')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Harga Per Malam (Rp)</label>
          <input type="number" name="harga" class="form-control @error('harga') is-invalid @enderror" value="{{ old('harga') }}" placeholder="Contoh: 500000" required>
          @error('harga')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Jumlah Stok Kamar</label>
          <input type="number" name="jumlah_kamar" class="form-control @error('jumlah_kamar') is-invalid @enderror" value="{{ old('jumlah_kamar') }}" placeholder="Contoh: 10" required>
          @error('jumlah_kamar')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Foto Kamar</label>
          <input type="file" name="foto" id="img" class="form-control @error('foto') is-invalid @enderror" accept="image/*">
          <small class="text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB.</small>
          @error('foto')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          
          <div class="mt-2">
            <img class="img-preview img-fluid rounded-3 style-preview" style="max-height: 150px; display: none;">
          </div>
        </div>

        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Pilih Fasilitas Kamar</label>
          <select name="fasilitas[]" class="form-select select2-multiple @error('fasilitas') is-invalid @enderror" multiple="multiple">
            @foreach($fasilitas as $item)
              <option value="{{ $item->id }}" {{ (is_array(old('fasilitas')) && in_array($item->id, old('fasilitas'))) ? 'selected' : '' }}>
                {{ $item->nama_fasilitas }}
              </option>
            @endforeach
          </select>
          <small class="text-muted">Ketik nama fasilitas untuk mencari dan pilih lebih dari satu.</small>
          @error('fasilitas')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Deskripsi Kamar</label>
          <textarea name="deskripsi" id="myeditor" class="form-control @error('deskripsi') is-invalid @enderror" rows="4" placeholder="Penjelasan fasilitas kamar...">{{ old('deskripsi') }}</textarea>
          @error('deskripsi')
            <div class="text-danger small mt-1">{{ $message }}</div>
          @enderror
        </div>
      </div>

      <button type="submit" class="btn btn-primary rounded-3 px-4">Simpan Kamar</button>
    </form>
  </div>
</div>
@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.ckeditor.com/4.21.0/standard/ckeditor.js"></script>

<script>
  $(document).ready(function() {
    $('.select2-multiple').select2({
      theme: 'bootstrap-5',
      placeholder: "-- Pilih Fasilitas --",
      allowClear: true
    });
  });

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
  $('#formKamar').on('submit', function(e) {
    if (CKEDITOR.instances.myeditor) {
      CKEDITOR.instances.myeditor.updateElement();
    }

    var yakin = confirm("Apakah Anda yakin ingin menyimpan kamar ini?");
    if (!yakin) {
      e.preventDefault();
    }
  });
</script>
@endpush