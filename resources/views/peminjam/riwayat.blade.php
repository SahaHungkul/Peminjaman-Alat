@extends('layouts.app')

@section('content')
    <h3>Riwayat Peminjaman Saya</h3>
    <div class="card mt-3">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Alat</th>
                        <th>Waktu peminjaman</th>
                        <th>Status</th>
                        <th>Denda</th>
                        <th class="text-center">aksi</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($loans as $loan)
                        <tr>
                            <td>{{ $loan->tool->nama_alat }} x{{ $loan->qty }}</td>
                            <td>{{ $loan->tanggal_pinjam->format('Y-m-d') }} -
                                {{ $loan->tanggal_kembali_rencana->format('Y-m-d') }}</td>
                            <td>
                                @if ($loan->status == 'pending')
                                    <span class="badge bg-warning text-dark">Menunggu Persetujuan</span>
                                @elseif($loan->status == 'disetujui')
                                    <span class="badge bg-primary">Sedang Dipinjam</span>
                                @elseif($loan->status == 'kembali')
                                    <span class="badge bg-success">Dikembalikan</span>
                                @elseif($loan->status == 'menunggu_konfirmasi')
                                    <span class="badge bg-primary">Selesai</span>
                                @elseif($loan->status == 'ditolak')
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif

                            </td>
                            <td>
                                @if ($loan->denda > 0)
                                    <div class="fw-bold text-danger">
                                        Rp {{ number_format($loan->denda, 0, ',', '.') }}
                                    </div>
                                    @if ($loan->status_denda == 'belum_bayar')
                                        <span class="badge bg-danger">Belum Lunas</span>
                                    @else
                                        <span class="badge bg-success">Lunas</span>
                                    @endif
                                @else
                                    <span class="text-muted small"> - </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="d-flex flex-column gap-2 align-items-center">
                                    {{-- Tombol Kembalikan --}}
                                    @if ($loan->status == 'disetujui')
                                        <form action="{{ route('peminjam.return', $loan->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm w-100">
                                                <i class="bi bi-arrow-return-left"></i> Kembalikan
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Tombol Bayar Denda --}}
                                    @if ($loan->denda > 0 && $loan->status_denda == 'belum_bayar')
                                        <button type="button" class="btn btn-outline-danger btn-sm w-100"
                                            data-bs-toggle="modal" data-bs-target="#paymentModal"
                                            data-loan-id="{{ $loan->id }}" data-denda="{{ $loan->denda }}">
                                            <i class="bi bi-cash"></i> Bayar Denda
                                        </button>
                                    @endif

                                    {{-- Jika tidak ada aksi --}}
                                    @if ($loan->status != 'disetujui' && !($loan->denda > 0 && $loan->status_denda == 'belum_bayar'))
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span>{{ $loan->catatan }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">Belum ada riwayat peminjaman.</td>
                        </tr>
                    @endforelse
                </tbody>
                <!-- Modal Payment -->
                <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="paymentModalLabel">
                                    <i class="bi bi-credit-card"></i> Pembayaran Denda
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="paymentForm">
                                @csrf
                                <input type="hidden" name="loan_id" id="loan_id">
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Jumlah Denda</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="text" class="form-control" id="denda_amount" readonly>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Metode Pembayaran</label>
                                        <select class="form-select" name="payment_method" id="payment_method" required>
                                            <option value="">Pilih Metode Pembayaran</option>
                                            <option value="credit_card">Kartu Kredit</option>
                                            <option value="bank_transfer">Transfer Bank</option>
                                            <option value="qris">QRIS</option>
                                            <option value="alfamart">Alfamart/Indomaret</option>
                                        </select>
                                    </div>

                                    <div class="alert alert-info">
                                        <i class="bi bi-info-circle"></i>
                                        Pembayaran akan diproses melalui Midtrans. Anda akan diarahkan ke halaman pembayaran
                                        yang aman.
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary" id="btnProcessPayment">
                                        <i class="bi bi-cash-stack"></i> Proses Pembayaran
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </table>
        </div>
        <div class="mt-3">{{ $loans->links('pagination::bootstrap-5') }}</div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const paymentModal = document.getElementById('paymentModal');

            if (paymentModal) {
                paymentModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const loanId = button.getAttribute('data-loan-id');
                    const denda = button.getAttribute('data-denda');

                    document.getElementById('loan_id').value = loanId;
                    document.getElementById('denda_amount').value = formatRupiah(denda);
                });
            }

            const paymentForm = document.getElementById('paymentForm');
            if (paymentForm) {
                paymentForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const loanId = document.getElementById('loan_id').value;
                    const paymentMethod = document.getElementById('payment_method').value;

                    if (!paymentMethod) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Peringatan',
                            text: 'Silakan pilih metode pembayaran terlebih dahulu!'
                        });
                        return;
                    }

                    const btnProcess = document.getElementById('btnProcessPayment');
                    btnProcess.disabled = true;
                    btnProcess.innerHTML = '<i class="bi bi-hourglass-split"></i> Memproses...';

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                    if (!csrfToken) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'CSRF token tidak ditemukan'
                        });
                        btnProcess.disabled = false;
                        btnProcess.innerHTML = '<i class="bi bi-cash-stack"></i> Proses Pembayaran';
                        return;
                    }

                    const formData = {
                        loan_id: loanId,
                        payment_method: paymentMethod
                    };

                    console.log('Sending data:', formData);

                    fetch('{{ route('peminjam.process-payment') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => {
                            console.log('Response status:', response.status);
                            if (!response.ok) {
                                return response.json().then(err => Promise.reject(err));
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Response data:', data);

                            if (data.error) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal',
                                    text: data.error
                                });
                                btnProcess.disabled = false;
                                btnProcess.innerHTML =
                                    '<i class="bi bi-cash-stack"></i> Proses Pembayaran';
                                return;
                            }

                            if (data.snap_token) {
                                // Cara manual menutup modal tanpa Bootstrap JS
                                const modalElement = document.getElementById('paymentModal');
                                if (modalElement) {
                                    modalElement.classList.remove('show');
                                    modalElement.style.display = 'none';
                                    const backdrop = document.querySelector('.modal-backdrop');
                                    if (backdrop) {
                                        backdrop.remove();
                                    }
                                    document.body.classList.remove('modal-open');
                                    document.body.style.overflow = '';
                                }

                                if (typeof window.snap === 'undefined') {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Midtrans Snap tidak terload. Silakan refresh halaman.'
                                    });
                                    btnProcess.disabled = false;
                                    btnProcess.innerHTML =
                                        '<i class="bi bi-cash-stack"></i> Proses Pembayaran';
                                    return;
                                }

                                window.snap.pay(data.snap_token, {
                                    onSuccess: function(result) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Berhasil!',
                                            text: 'Pembayaran denda berhasil dilakukan',
                                            timer: 2000,
                                            showConfirmButton: false
                                        }).then(() => {
                                            window.location.href =
                                                '{{ route('peminjam.payment-success') }}?order_id=' +
                                                result.order_id;
                                        });
                                    },
                                    onPending: function(result) {
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Pending',
                                            text: 'Pembayaran sedang diproses',
                                            confirmButtonText: 'OK'
                                        }).then(() => {
                                            window.location.reload();
                                        });
                                    },
                                    onError: function(result) {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            text: 'Pembayaran gagal: ' + (result
                                                .status_message ||
                                                'Unknown error')
                                        });
                                        btnProcess.disabled = false;
                                        btnProcess.innerHTML =
                                            '<i class="bi bi-cash-stack"></i> Proses Pembayaran';
                                    }
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Tidak mendapatkan snap_token dari server'
                                });
                                btnProcess.disabled = false;
                                btnProcess.innerHTML =
                                    '<i class="bi bi-cash-stack"></i> Proses Pembayaran';
                            }
                        })
                        .catch(error => {
                            console.error('Fetch error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: error.error || 'Terjadi kesalahan: ' + (error.message ||
                                    'Unknown error')
                            });
                            btnProcess.disabled = false;
                            btnProcess.innerHTML = '<i class="bi bi-cash-stack"></i> Proses Pembayaran';
                        });
                });
            }
        });

        function formatRupiah(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(amount);
        }
    </script>
    {{-- console.log('Meta CSRF:', document.querySelector('meta[name="csrf-token"]'));
        console.log('Meta content:', document.querySelector('meta[name="csrf-token"]')?.content);
        console.log('Meta CSRF:', document.querySelector('meta[name="csrf-token"]'));
        console.log('Meta content:', document.querySelector('meta[name="csrf-token"]')?.content); --}}
@endsection
