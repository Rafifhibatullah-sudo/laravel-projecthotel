@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0"><i class="fas fa-calendar-check me-2 "></i>Data Reservasi Hotel</h2>
    <p class="text-muted small mb-0">Kelola konfirmasi booking kamar dan laporan reservasi tamu.</p>
  </div>
  <div>
    <a href="{{ route('reservasi.export') }}" class="btn btn-success rounded-3 shadow-sm">
      <i class="fas fa-file-excel me-1"></i> Download Laporan Excel
    </a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
  <div class="card-body p-4">
    <div class="table-responsive">
      <table class=" table table-striped table-bordered table table-hover align-middle datatable w-100">
        <thead class="table-light">
          <tr>
            <th width="5%">No</th>
            <th>Kode Booking</th>
            <th>Tamu</th>
            <th>Kamar</th>
            <th>Check In / Out</th>
            <th>Total</th>
            <th>Status</th>
            <th width="15%" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($reservasis as $row)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td><span class="badge bg-secondary font-monospace">{{ $row->kode_booking }}</span></td>
            <td>
              <div class="fw-bold">{{ $row->nama_pemesan }}</div>
              <small class="text-muted d-block"><i class="fas fa-phone fa-xs me-1"></i>{{ $row->no_hp }}</small>
            </td>
            <td>
              <span class="fw-semibold">{{ $row->kamar ? $row->kamar->nama_kamar : 'Kamar Dihapus' }}</span>
              <small class="text-muted d-block">{{ $row->jumlah_kamar }} Unit</small>
            </td>
            <td>
              <small class="d-block text-success fw-semibold"><i class="fas fa-sign-in-alt me-1"></i>{{ \Carbon\Carbon::parse($row->check_in)->format('d M Y') }}</small>
              <small class="d-block text-danger fw-semibold"><i class="fas fa-sign-out-alt me-1"></i>{{ \Carbon\Carbon::parse($row->check_out)->format('d M Y') }}</small>
            </td>
            <td class="fw-bold">Rp {{ number_format($row->total_harga, 0, ',', '.') }}</td>
            <td>
              @if($row->status == 'pending')
                <span class="badge bg-primary text-white"><i class="fas fa-clock me-1"></i>Pending</span>
              @elseif($row->status == 'confirmed')
                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Confirmed</span>
              @else
                <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Cancelled</span>
              @endif
            </td>
            <td class="text-center text-nowrap">
  <!-- 1. Tombol Konfirmasi (Ubah ke Confirmed) -->
  @if($row->status == 'pending')
    <form action="{{ route('reservasi.updateStatus', $row->id) }}" method="POST" class="d-inline">
      @csrf
      @method('PUT')
      <input type="hidden" name="status" value="confirmed">
      <button type="submit" class="btn btn-sm btn-success rounded-2 px-2" title="Konfirmasi">
        <i class="fas fa-check"></i>
      </button>
    </form>
  @endif

  <!-- 2. Tombol Pembatalan (Ubah ke Cancelled) -->
  @if($row->status != 'cancelled')
    <form action="{{ route('reservasi.updateStatus', $row->id) }}" method="POST" class="d-inline">
      @csrf
      @method('PUT')
      <input type="hidden" name="status" value="cancelled">
      <button type="submit" class="btn btn-sm btn-secondary text-white rounded-2 px-2" title="Batalkan" onclick="return confirm('Batalkan pesanan ini?');">
        <i class="fas fa-ban"></i>
      </button>
    </form>
  @endif

  <!-- 3. Tombol Hapus Data -->
  <form action="{{ route('reservasi.destroy', $row->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus permanen data ini?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger rounded-2 px-2" title="Hapus">
      <i class="fas fa-trash"></i>
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