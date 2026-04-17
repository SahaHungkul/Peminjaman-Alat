@extends('layouts.app')

@section('content')
    <h3>Permintaan Peminjaman Masuk</h3>
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">Monitor Peminjaman</div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Peminjam</th>
                            <th>Alat</th>
                            <th>Tgl Pinjam</th>
                            <th>Rencana Kembali</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($loans as $loan)
                            <tr>
                                <td>{{ $loan->user->name }}</td>
                                {{-- Pastikan relasi tool atau details sesuai dengan logic "Many tools" Anda --}}
                                <td>{{ $loan->tool->nama_alat ?? 'Alat tidak ditemukan' }} x{{ $loan->qty }}</td>
                                <td>{{ $loan->tanggal_pinjam->format('Y-m-d') }}</td>
                                <td>{{ $loan->tanggal_kembali_rencana->format('Y-m-d') }}</td>
                                <td>
                                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalSetuju{{ $loan->id }}">Setujui</button>

                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalTolak{{ $loan->id }}">
                                        Tolak
                                    </button>
                                </td>
                            </tr>

                            <div class="modal fade" id="modalSetuju{{ $loan->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ url('/petugas/approve/' . $loan->id) }}" method="POST"
                                            enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Konfirmasi Persetujuan #{{ $loan->id }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p class="text-muted">Peminjam: <strong>{{ $loan->user->name }}</strong>
                                                </p>
                                                <p>Silakan upload <strong>Foto Kondisi Awal</strong> alat sebelum
                                                    diserahkan.</p>

                                                <div class="mb-3">
                                                    <label for="gambar_awal" class="form-label fw-bold">Gambar Kondisi
                                                        Awal</label>
                                                    <input type="file" class="form-control" name="gambar_awal"
                                                        accept="image/*" required>
                                                    <div class="form-text text-muted">Format: jpg, png, jpeg. Maks: 2MB.
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Catatan Kondisi</label>
                                                    <textarea class="form-control" name="catatan" rows="3"
                                                        placeholder="Contoh: Kamera dalam kondisi baterai penuh, ada sedikit goresan di layar..."></textarea>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-success">Setujui & Kirim</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="modalTolak{{ $loan->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ url('/petugas/reject/' . $loan->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title text-danger">Tolak Peminjaman #{{ $loan->id }}
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Apakah Anda yakin ingin menolak peminjaman oleh
                                                    <strong>{{ $loan->user->name }}</strong>?</p>

                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Alasan Penolakan</label>
                                                    <textarea class="form-control" name="catatan" rows="3" placeholder="Tuliskan alasan penolakan di sini..."
                                                        required></textarea>
                                                    <div class="form-text">Catatan ini akan terlihat oleh peminjam.</div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-danger">Ya, Tolak Pengajuan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada permintaan baru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $loans->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>

    <h3>Daftar Sedang Dipinjam (Belum Kembali)</h3>
    <div class="card mb-3">
    <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
        <span>Monitor Peminjaman</span>
        <span class="badge bg-light text-dark">{{ $activeLoans->total() }} Transaksi Aktif</span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th>Bukti Awal</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($activeLoans as $active)
                        <tr>
                            <td>{{ $active->user->name }}</td>
                            <td>{{ $active->tool->nama_alat }} x{{ $active->qty }}</td>
                            <td>
                                <small>{{ $active->tanggal_pinjam->format('d M Y') }}</small><br>
                                <small class="text-muted">Hingga: {{ $active->tanggal_kembali_rencana->format('d M Y') }}</small>
                            </td>
                            <td>
                                @if (now()->startOfDay()->gt($active->tanggal_kembali_rencana->startOfDay()))
                                    <span class="badge bg-danger">Terlambat</span>
                                @else
                                    <span class="badge bg-primary">Sedang Dipinjam</span>
                                @endif
                            </td>
                            <td>
                                @if ($active->denda_saat_ini > 0)
                                    <span class="text-danger fw-bold">Rp {{ number_format($active->denda_saat_ini, 0, ',', '.') }}</span>
                                    <small class="d-block text-muted">{{ now()->startOfDay()->diffInDays($active->tanggal_kembali_rencana->startOfDay()) }} Hari</small>
                                @else
                                    <span class="text-success">0</span>
                                @endif
                            </td>
                            {{-- Bukti Awal --}}
                            <td>
                                @if($active->gambar_awal)
                                    <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#modalBuktiAwal{{ $active->id }}">
                                        <i class="bi bi-image"></i> Lihat
                                    </button>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            {{-- Aksi --}}
                            <td class="text-center">
                                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalKembali{{ $active->id }}">
                                    <i class="bi bi-arrow-return-left"></i> Kembalikan
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalBuktiAwal{{ $active->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Kondisi Awal Alat</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body text-center">
                                        <img src="{{ asset('storage/' . $active->gambar_awal) }}" class="img-fluid rounded mb-3" alt="Bukti Awal">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modalKembali{{ $active->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ url('/petugas/return/' . $active->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Pengembalian</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="alert alert-info">
                                                Alat: <strong>{{ $active->tool->nama_alat }} ({{ $active->qty }} unit)</strong><br>
                                                Peminjam: <strong>{{ $active->user->name }}</strong>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Foto Kondisi Akhir</label>
                                                <input type="file" name="gambar" class="form-control" accept="image/*" required>
                                                <small class="text-muted">Wajib upload foto kondisi alat saat ini.</small>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Catatan Pengembalian</label>
                                                <textarea name="catatan" class="form-control" rows="3" placeholder="Contoh: Alat kembali dengan lengkap, ada sedikit kotor di body..."></textarea>
                                            </div>

                                            @if($active->denda_saat_ini > 0)
                                            <div class="p-2 bg-danger-subtle text-danger border border-danger rounded">
                                                <i class="bi bi-exclamation-circle"></i>
                                                <strong>Denda Terdeteksi: Rp {{ number_format($active->denda_saat_ini, 0, ',', '.') }}</strong>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Konfirmasi Pengembalian Alat</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">{{ $activeLoans->links('pagination::bootstrap-5') }}</div>
    </div>
</div>

    <h3>Daftar Sudah Dikembalikan</h3>
    <div class="card mb-3">
        <div class="card-header bg-info text-white">Monitor Peminjaman</div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Alat</th>
                        <th>Tanggal</th>
                        <th>denda & Status</th>
                        <th style="width: 10%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sudahDikembalikan as $sudah)
                        <tr>
                            <td>{{ $sudah->user->name }}</td>
                            <td>{{ $sudah->tool->nama_alat }} x{{ $sudah->qty }}</td>
                            <td>{{ $sudah->tanggal_pinjam }} {{ $sudah->tanggal_kembali_rencana->format('Y-m-d') }} <br>
                                <small class="text-muted">Kembali:
                                    {{ $sudah->tanggal_kembali_aktual->format('Y-m-d') }}</small>
                            </td>
                            <td>
                                @if ($sudah->denda > 0)
                                    <div class="fw-bold text-danger">
                                        Rp {{ number_format($sudah->denda, 0, ',', '.') }}
                                    </div>
                                    @if ($sudah->status_denda == 'belum_bayar')
                                        <span class="badge bg-danger">Belum Lunas</span>
                                        <form action="{{ route('petugas.bayar', $sudah->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-link btn-sm p-0 text-success fw-bold"
                                                onclick="return confirm('Konfirmasi pelunasan denda?')">
                                                <i class="bi bi-currency-dollar"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="badge bg-success">Lunas</span>
                                    @endif
                                @else
                                    <span class="text-muted small"> - </span>
                                @endif
                                {{-- <span class="badge bg-primary">{{ $sudah->status }}</span> --}}
                            </td>
                            <td>
                                @if ($sudah->gambar)
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                        data-bs-target="#modalBukti{{ $sudah->id }}">
                                        <i class="bi bi-camera"></i>
                                    </button>

                                    <div class="modal fade" id="modalBukti{{ $sudah->id }}" tabindex="-1"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Bukti Pengembalian:
                                                        {{ $sudah->tool->nama_alat }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body text-center ">
                                                    <img src="{{ asset('storage/' . $sudah->gambar) }}"
                                                        class="img-fluid rounded shadow-sm mb-2" alt="Foto Bukti">
                                                    {{-- <div class="text-start mt-3 p-3  border rounded">
                                                        <p class="mb-1"><strong>Denda:</strong> Rp
                                                            {{ number_format($sudah->denda, 0, ',', '.') }}</p>
                                                        <p class="mb-0 text-muted"><strong>Catatan:</strong>
                                                            {{ $sudah->catatan_petugas ?? 'Tidak ada catatan.' }}</p>
                                                    </div> --}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted small">Tidak ada bukti</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">{{ $sudahDikembalikan->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
@endsection
