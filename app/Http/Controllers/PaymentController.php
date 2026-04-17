<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function processPayment(Request $request)
    {
        Log::info('Process payment called', $request->all());

        $request->validate([
            'loan_id' => 'required|exists:loans,id',
            'payment_method' => 'required|string'
        ]);

        $loan = Loan::findOrFail($request->loan_id);

        if ($loan->user_id != Auth::id()) {
            return response()->json(['error' => 'Anda tidak memiliki akses ke peminjaman ini'], 403);
        }

        if ($loan->status_denda == 'lunas') {
            return response()->json(['error' => 'Denda sudah lunas'], 400);
        }

        if ($loan->denda <= 0) {
            return response()->json(['error' => 'Tidak ada denda yang harus dibayar'], 400);
        }

        $orderId = 'DENDA-' . $loan->id . '-' . time() . '-' . Auth::id();

        // Parameter untuk Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $loan->denda,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? '',
            ],
            'item_details' => [
                [
                    'id' => 'denda_' . $loan->id,
                    'price' => (int) $loan->denda,
                    'quantity' => 1,
                    'name' => 'Denda Keterlambatan Peminjaman',
                ]
            ],
            // HAPUS atau KOMENTAR bagian enabled_payments
            // Biarkan Midtrans yang menentukan payment channels yang tersedia
        ];

        // JANGAN tambahkan enabled_payments agar semua metode pembayaran tersedia
        // atau jika ingin spesifik, gunakan seperti ini:
        // $params['enabled_payments'] = ['credit_card', 'bank_transfer', 'qris', 'cstore'];

        try {
            Log::info('Calling Midtrans Snap', $params);
            $snapToken = Snap::getSnapToken($params);
            Log::info('Snap token received: ' . $snapToken);

            $transaction = Transaction::create([
                'order_id' => $orderId,
                'loan_id' => $loan->id,
                'amount' => $loan->denda,
                'status' => 'pending',
                'snap_token' => $snapToken,
                'payment_method' => $request->payment_method,
            ]);

            return response()->json([
                'success' => true,
                'snap_token' => $snapToken,
                'order_id' => $orderId,
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $orderId = $request->query('order_id');
        $transaction = Transaction::where('order_id', $orderId)->first();

        if ($transaction && $transaction->status == 'pending') {
            $transaction->update(['status' => 'success']);
            $transaction->loan->update(['status_denda' => 'lunas']);
        }

        return redirect()->route('peminjam.riwayat')
            ->with('success', 'Pembayaran denda berhasil!');
    }

    public function paymentFailed(Request $request)
    {
        return redirect()->route('peminjam.riwayat.index')
            ->with('error', 'Pembayaran gagal atau dibatalkan');
    }

    public function handleNotification(Request $request)
    {
        try {
            $notif = new Notification();
            $transaction = Transaction::where('order_id', $notif->order_id)->first();

            if ($transaction) {
                if ($notif->transaction_status == 'capture' || $notif->transaction_status == 'settlement') {
                    $transaction->update(['status' => 'success']);
                    $transaction->loan->update(['status_denda' => 'lunas']);
                } elseif ($notif->transaction_status == 'deny' || $notif->transaction_status == 'expire') {
                    $transaction->update(['status' => 'failed']);
                }
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
