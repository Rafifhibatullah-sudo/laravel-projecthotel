@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0"><i class="fas fa-edit me-2"></i>Edit Data Kamar</h2>
    <p class="text-muted small mb-0">Ubah formulir berikut untuk memperbarui data kamar.</p>
  </div>
  <a href="{{ route('kamar.index') }}" class="btn btn-outline-secondary rounded-3">Kembali</a>
</div>

<div class="card border-0 shadow-sm rounded-4 mb-4" data-aos="fade-up">
  <div class="card-body p-4">
    <form action="{{ route('kamar.update', $kamar->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Nama Kamar</label>
          <input type="text" name="nama_kamar" class="form-control" value="{{ $kamar->nama_kamar }}" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Tipe Kamar</label>
          <select name="tipe_kamar" class="form-select" required>
            <option value="Single" {{ $kamar->tipe_kamar == 'Single' ? 'selected' : '' }}>Single Room</option>
            <option value="Double" {{ $kamar->tipe_kamar == 'Double' ? 'selected' : '' }}>Double Room</option>
            <option value="Deluxe" {{ $kamar->tipe_kamar == 'Deluxe' ? 'selected' : '' }}>Deluxe Room</option>
            <option value="Suite" {{ $kamar->tipe_kamar == 'Suite' ? 'selected' : '' }}>Suite Room</option>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Harga Per Malam (Rp)</label>
          <input type="number" name="harga" class="form-control" value="{{ $kamar->harga }}" required>
        </div>

        <div class="col-md-6 mb-3">
          <label class="form-label fw-semibold">Jumlah Stok Kamar</label>
          <input type="number" name="jumlah_kamar" class="form-control" value="{{ $kamar->jumlah_kamar }}" required>
        </div>

        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Foto Kamar</label>
          @if($kamar->foto)
            <div class="mb-2">
              <img src="{{ asset('storage/' . $kamar->foto) }}" class="rounded-3 shadow-sm border" width="120" height="90" style="object-fit: cover;" alt="Foto Saat Ini">
            </div>
          @endif
          <input type="file" name="foto" class="form-control" accept="image/*">
          <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto saat ini.</small>
        </div>

        <!-- Dynamic Select2 Multi-Select Edit -->
        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Pilih Fasilitas Kamar</label>
          <select name="fasilitas_id[]" class="form-select select2-multiple" multiple="multiple">
            @foreach($fasilitas as $item)
              <option value="{{ $item->id }}" {{ $kamar->fasilitas->contains($item->id) ? 'selected' : '' }}>
                {{ $item->nama_fasilitas }}
              </option>
            @endforeach
          </select>
          <small class="text-muted">Ketik nama fasilitas untuk mencari dan memperbarui pilihan.</small>
        </div>

        <div class="col-md-12 mb-3">
          <label class="form-label fw-semibold">Deskripsi Kamar</label>
          <textarea name="deskripsi" id="myeditor" class="form-control" rows="4">{{ $kamar->deskripsi }}</textarea>
        </div>
      </div>

      <button type="submit" class="btn btn-primary rounded-3 px-4">Update Kamar</button>
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