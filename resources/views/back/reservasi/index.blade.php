@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-3 mb-4 border-bottom" data-aos="fade-down">
  <div>
    <h2 class="h3 fw-bold text-dark mb-0"><i class="fas fa-calendar-check me-2"></i>Data Reservasi Hotel</h2>
    <p class="text-muted small mb-0">Kelola konfirmasi booking kamar, check-in/out, dan bukti pembayaran tamu.</p>
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
      <table class="table table-striped table-bordered table-hover align-middle datatable w-100">
        <thead class="table-light">
          <tr>
            <th width="5%">No</th>
            <th>Kode Booking</th>
            <th>Tamu</th>
            <th>Kamar</th>
            <th>Check In / Out</th>
            <th>Total</th>
            <th>Status</th>
            <th width="10%" class="text-center">Aksi</th>
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
                <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Pending</span>
              @elseif($row->status == 'confirmed')
                <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Confirmed</span>
              @elseif($row->status == 'in')
                <span class="badge bg-info text-dark"><i class="fas fa-door-open me-1"></i>In (Check-In)</span>
              @elseif($row->status == 'out')
                <span class="badge bg-secondary"><i class="fas fa-door-closed me-1"></i>Out (Check-Out)</span>
              @else
                <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Cancelled</span>
              @endif
            </td>
            <td class="text-center">
              <!-- Toggle Menu Dropdown (Poin 5) -->
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Menu Aksi
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                  
                  <!-- Option 1: Konfirmasi (Hanya muncul jika status pending) -->
                  @if($row->status == 'pending')
                  <li>
                    <form action="{{ route('reservasi.updateStatus', $row->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="status" value="confirmed">
                      <button type="submit" class="dropdown-item text-success"><i class="fas fa-check me-2"></i>Konfirmasi Booking</button>
                    </form>
                  </li>
                  @endif

                  <!-- Option 2: Check-In / In (Setelah status confirmed) -->
                  @if($row->status == 'confirmed')
                  <li>
                    <form action="{{ route('reservasi.updateStatus', $row->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="status" value="in">
                      <button type="submit" class="dropdown-item text-info"><i class="fas fa-key me-2"></i>Tamu Check-In (In)</button>
                    </form>
                  </li>
                  @endif

                  <!-- Option 3: Check-Out / Out (Setelah status in) -->
                  @if($row->status == 'in')
                  <li>
                    <form action="{{ route('reservasi.updateStatus', $row->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="status" value="out">
                      <button type="submit" class="dropdown-item text-secondary"><i class="fas fa-walking me-2"></i>Tamu Check-Out (Out)</button>
                    </form>
                  </li>
                  @endif

                  <!-- Option 4: Cetak Bukti Pembayaran -->
                  @if($row->status != 'cancelled')
                  <li>
                    <a href="{{ route('reservasi.cetak', $row->id) }}" target="_blank" class="dropdown-item text-primary">
                      <i class="fas fa-print me-2"></i>Bukti Pembayaran
                    </a>
                  </li>
                  @endif

                  <li><hr class="dropdown-divider"></li>

                  <!-- Option 5: Batalkan Booking -->
                  @if($row->status != 'cancelled' && $row->status != 'out')
                  <li>
                    <form action="{{ route('reservasi.updateStatus', $row->id) }}" method="POST">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="status" value="cancelled">
                      <button type="submit" class="dropdown-item text-warning" onclick="return confirm('Batalkan pemesanan ini?');">
                        <i class="fas fa-ban me-2"></i>Batalkan
                      </button>
                    </form>
                  </li>
                  @endif

                  <!-- Option 6: Hapus Permanent -->
                  <li>
                    <form action="{{ route('reservasi.destroy', $row->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Hapus data reservasi ini secara permanen?');">
                        <i class="fas fa-trash me-2"></i>Hapus Data
                      </button>
                    </form>
                  </li>

                </ul>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection