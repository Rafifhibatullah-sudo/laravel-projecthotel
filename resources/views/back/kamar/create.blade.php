@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0">Tambah Kamar Baru</h2>
    <p class="text-muted small mb-0">Isi formulir berikut untuk memasukkan data kamar beserta fasilitasnya.</p>
  </div>
  <a href="{{ route('kamar.index') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
  <div class="card-body p-4">
    <form action="{{ route('kamar.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Nama Kamar</label>
          <input type="text" name="nama_kamar" class="form-control" placeholder="Contoh: Deluxe Room" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Tipe Kamar</label>
          <select name="tipe_kamar" class="form-select" required>
            <option value="" hidden>-- Pilih Tipe --</option>
            <option value="Single">Single Room</option>
            <option value="Double">Double Room</option>
            <option value="Deluxe">Deluxe Room</option>
            <option value="Suite">Suite Room</option>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Harga Per Malam (Rp)</label>
          <input type="number" name="harga" class="form-control" placeholder="Contoh: 500000" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Jumlah Stok Kamar</label>
          <input type="number" name="jumlah_kamar" class="form-control" placeholder="Contoh: 10" required>
        </div>

        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Foto Kamar</label>
          <input type="file" name="foto" id="img" class="form-control" accept="image/*">
          <small class="text-muted">Format: JPG, PNG, WEBP. Maksimal 2MB.</small>
          
          <!-- Container Preview Foto -->
          <div class="mt-2">
            <img class="img-preview img-fluid rounded-3 style-preview" style="max-height: 150px; display: none;">
          </div>
        </div>

        <!-- Dynamic Select2 Multi-Select -->
        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Pilih Fasilitas Kamar</label>
          <!-- PERBAIKAN: Atribut name disesuaikan menjadi fasilitas[] -->
          <select name="fasilitas[]" class="form-select select2-multiple" multiple="multiple">
            @foreach($fasilitas as $item)
              <option value="{{ $item->id }}">{{ $item->nama_fasilitas }}</option>
            @endforeach
          </select>
          <small class="text-muted">Ketik nama fasilitas untuk mencari dan pilih lebih dari satu.</small>
        </div>

        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Deskripsi Kamar</label>
          <textarea name="deskripsi" id="myeditor" class="form-control" rows="4" placeholder="Penjelasan fasilitas kamar..."></textarea>
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

  if(document.getElementById('myeditor')){
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
</script>
@endpush