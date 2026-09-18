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
    <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

<div class="card border-0 shadow-sm rounded-4" data-aos="fade-up">
  <div class="card-body p-4">
    <div class="table-responsive" style="min-height: 380px;">
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
            <th width="12%" class="text-center">Aksi</th>
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
            <td class="fw-bold">Rp {{ number_format($row->total_harga ?? $row->total_bayar ?? 0, 0, ',', '.') }}</td>
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
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle rounded-3" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Menu Aksi
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                  
                  <!-- Option 1: Modal Rincian Reservasi -->
                  <li>
                    <button type="button" class="dropdown-item text-primary fw-medium" data-bs-toggle="modal" data-bs-target="#modalRincian{{ $row->id }}">
                      <i class="fas fa-file-alt me-2"></i>Rincian Reservasi
                    </button>
                  </li>

                  <!-- Option 2: Modal Bukti Pembayaran -->
                  <li>
                    <button type="button" class="dropdown-item text-info fw-medium" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $row->id }}">
                      <i class="fas fa-image me-2"></i>Bukti Pembayaran
                    </button>
                  </li>

                  <!-- Option 3: Konfirmasi Booking -->
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

                  <!-- Option 4: Check-In -->
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

                  <!-- Option 5: Check-Out -->
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

                  <!-- Option 6: Cetak Struk -->
                  @if($row->status != 'cancelled')
                  <li>
                    <a href="{{ route('reservasi.cetak', $row->id) }}" target="_blank" class="dropdown-item text-secondary">
                      <i class="fas fa-print me-2"></i>Cetak Struk
                    </a>
                  </li>
                  @endif

                  <li><hr class="dropdown-divider"></li>

                  <!-- Option 7: Batalkan -->
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

                  <!-- Option 8: Hapus Data -->
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

<!-- ========================================== -->
<!-- MODAL 1 & 2: LOOPING DATA MODAL -->
<!-- ========================================== -->
@foreach($reservasis as $row)

<!-- MODAL RINCIAN RESERVASI -->
<div class="modal fade" id="modalRincian{{ $row->id }}" tabindex="-1" aria-labelledby="modalRincianLabel{{ $row->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content border-0 shadow-lg rounded-4 text-start">
      <div class="modal-header bg-primary text-white py-3">
        <h5 class="modal-title fw-bold fs-6" id="modalRincianLabel{{ $row->id }}">
          <i class="fas fa-file-alt me-2"></i>Rincian Reservasi - {{ $row->kode_booking }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        
        <div class="text-center mb-3 pb-3 border-bottom">
          <span class="badge bg-primary-subtle text-primary border font-monospace px-3 py-2 fs-6">
            {{ $row->kode_booking }}
          </span>
          <div class="mt-2">
            @if($row->status == 'pending')
              <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i>Menunggu Konfirmasi (Pending)</span>
            @elseif($row->status == 'confirmed')
              <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i>Terkonfirmasi (Confirmed)</span>
            @elseif($row->status == 'in')
              <span class="badge bg-info text-dark"><i class="fas fa-door-open me-1"></i>Check-In (In)</span>
            @elseif($row->status == 'out')
              <span class="badge bg-secondary"><i class="fas fa-door-closed me-1"></i>Check-Out (Out)</span>
            @else
              <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i>Dibatalkan</span>
            @endif
          </div>
        </div>

        <h6 class="fw-bold text-dark mb-3"><i class="fas fa-user-tag text-primary me-2"></i>Informasi Pemesan</h6>
        
        <table class="table table-sm table-borderless small mb-0">
          <tr>
            <td class="text-muted" style="width: 130px;">Nama Lengkap</td>
            <td>: <strong class="text-dark">{{ $row->nama_pemesan }}</strong></td>
          </tr>
          <tr>
            <td class="text-muted">No. HP / WA</td>
            <td>: <strong class="text-dark">{{ $row->no_hp }}</strong></td>
          </tr>
          <tr>
            <td class="text-muted">Email</td>
            <td>: {{ $row->email ?? '-' }}</td>
          </tr>
          <tr>
            <td class="text-muted">Tipe Kamar</td>
            <td>: <strong class="text-primary">{{ $row->kamar ? $row->kamar->nama_kamar : '-' }}</strong></td>
          </tr>
          <tr>
            <td class="text-muted">Jumlah Kamar</td>
            <td>: {{ $row->jumlah_kamar }} Unit</td>
          </tr>
          <tr>
            <td class="text-muted">Tanggal Check In</td>
            <td>: <span class="text-success fw-bold"><i class="fas fa-calendar-check me-1"></i>{{ \Carbon\Carbon::parse($row->check_in)->format('d M Y') }}</span></td>
          </tr>
          <tr>
            <td class="text-muted">Tanggal Check Out</td>
            <td>: <span class="text-danger fw-bold"><i class="fas fa-calendar-times me-1"></i>{{ \Carbon\Carbon::parse($row->check_out)->format('d M Y') }}</span></td>
          </tr>
          <tr>
            <td class="text-muted">Total Tagihan</td>
            <td>: <strong class="text-success fs-6">Rp {{ number_format($row->total_harga ?? $row->total_bayar ?? 0, 0, ',', '.') }}</strong></td>
          </tr>
          <tr>
            <td class="text-muted">Catatan Tamu</td>
            <td>: <em>{{ $row->catatan ?? '-' }}</em></td>
          </tr>
        </table>

      </div>
      <div class="modal-footer bg-light py-2">
        <button type="button" class="btn btn-secondary btn-sm rounded-2" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL FOTO BUKTI PEMBAYARAN -->
<div class="modal fade" id="modalBukti{{ $row->id }}" tabindex="-1" aria-labelledby="modalBuktiLabel{{ $row->id }}" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg rounded-4 text-start">
      <div class="modal-header bg-info text-white py-3">
        <h5 class="modal-title fw-bold fs-6" id="modalBuktiLabel{{ $row->id }}">
          <i class="fas fa-image me-2"></i>Bukti Transfer - {{ $row->kode_booking }}
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4 text-center">
        @php
          // Cek beberapa variasi nama kolom database
          $pathBukti = $row->bukti_pembayaran ?? $row->bukti_bayar;
        @endphp

        @if(!empty($pathBukti))
          @php
            // Memastikan path tidak tertumpuk 'bukti_bayar/bukti_bayar/...'
            $urlGambar = Str::startsWith($pathBukti, 'bukti_bayar/') 
              ? asset('storage/' . $pathBukti) 
              : asset('storage/bukti_bayar/' . $pathBukti);
          @endphp

          <div class="border rounded-3 p-2 bg-light mb-3">
            <a href="{{ $urlGambar }}" target="_blank">
              <img src="{{ $urlGambar }}" class="img-fluid rounded-2 shadow-sm" style="max-height: 300px; object-fit: contain;" alt="Bukti Transfer">
            </a>
          </div>
          <small class="text-muted d-block mb-3"><i class="fas fa-search-plus me-1"></i>Klik gambar untuk memperbesar</small>
          
          @if($row->status == 'pending')
          <form action="{{ route('reservasi.updateStatus', $row->id) }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="status" value="confirmed">
            <button type="submit" class="btn btn-success btn-sm w-100 fw-bold py-2">
              <i class="fas fa-check-circle me-1"></i> Konfirmasi Pembayaran Valid
            </button>
          </form>
          @endif
        @else
          <div class="border rounded-3 p-4 bg-light text-muted d-flex flex-column align-items-center justify-content-center" style="min-height: 200px;">
            <i class="fas fa-receipt fa-3x mb-3 text-secondary opacity-50"></i>
            <span class="badge bg-warning text-dark px-3 py-2 rounded-pill mb-2">Belum Ada Bukti Transfer</span>
            <small class="text-center">Tamu belum mengunggah foto bukti pembayaran atau data transaksi lama.</small>
          </div>
        @endif
      </div>
      <div class="modal-footer bg-light py-2">
        <button type="button" class="btn btn-secondary btn-sm rounded-2" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endforeach

@endsection