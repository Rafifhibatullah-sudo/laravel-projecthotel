@extends('back.layout.template')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h3 class="fw-bold text-dark mb-0"><i class="fas fa-users me-2"></i>Manajemen User & Role</h3>
    <p class="text-muted small mb-0">Kelola akun admin dan resepsionis hotel.</p>
  </div>
  <button class="btn btn-primary rounded-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
    <i class="fas fa-plus me-1"></i> Tambah User Baru
  </button>
</div>

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
@endif

<div class="card border-0 shadow-sm rounded-4">
  <div class="card-body p-4">
    <div class="table-responsive">
      <table class="table table-striped table-bordered table table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>Role / Hak Akses</th>
            <th>Tanggal Dibuat</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $loop->iteration }}</td>
            <td class="fw-bold text-dark">{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              @if($user->role == 'admin')
                <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="fas fa-user-shield me-1"></i>Admin</span>
              @else
                <span class="badge bg-info text-dark px-3 py-2 rounded-pill"><i class="fas fa-user-tag me-1"></i>Resepsionis</span>
              @endif
            </td>
            <td class="small text-muted">{{ $user->created_at->format('d M Y') }}</td>
            <td class="text-center">
              <button class="btn btn-sm btn-secondary text-white rounded-2 me-1" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $user->id }}">
                <i class="fas fa-edit me-1"></i>edit
              </button>
              <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus user ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger rounded-2"><i class="fas fa-trash me-1"></i>hapus</button>
              </form>
            </td>
          </tr>

          <!-- Modal Edit User -->
          <div class="modal fade" id="modalEditUser{{ $user->id }}" tabindex="-1">
            <div class="modal-dialog">
              <div class="modal-content rounded-4 border-0">
                <div class="modal-header">
                  <h5 class="modal-title fw-bold">Edit User</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                  @csrf
                  @method('PUT')
                  <div class="modal-body">
                    <div class="mb-3">
                      <label class="form-label fw-semibold">Nama</label>
                      <input type="text" name="name" class="form-control rounded-3" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-semibold">Email</label>
                      <input type="email" name="email" class="form-control rounded-3" value="{{ $user->email }}" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-semibold">Role</label>
                      <select name="role" class="form-select rounded-3">
                        <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="resepsionis" {{ $user->role == 'resepsionis' ? 'selected' : '' }}>Resepsionis</option>
                      </select>
                    </div>
                    <div class="mb-3">
                      <label class="form-label fw-semibold">Password Baru (Opsional)</label>
                      <input type="password" name="password" class="form-control rounded-3" placeholder="Kosongkan jika tidak diganti">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" class="btn btn-primary rounded-3">Simpan Perubahan</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Modal Tambah User -->
<div class="modal fade" id="modalTambahUser" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content rounded-4 border-0">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Tambah User Baru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="name" class="form-control rounded-3" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Email</label>
            <input type="email" name="email" class="form-control rounded-3" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Role</label>
            <select name="role" class="form-select rounded-3">
              <option value="resepsionis">Resepsionis</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label fw-semibold">Password</label>
            <input type="password" name="password" class="form-control rounded-3" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary rounded-3">Tambah User</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection