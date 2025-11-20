<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\PembayaranSpp;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Tampilkan daftar tagihan belum dibayar
     */
    public function index()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->firstOrFail();
        
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();

        $tagihanBelumLunas = PembayaranSpp::where('siswa_id', $siswa->id_siswa)
            ->where('status', 'Belum Lunas')
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
            ->with('tahunAjaran')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();

        return view('siswa.tagihan', compact('siswa', 'tagihanBelumLunas', 'tahunAjaranAktif'));
    }

    /**
     * Tampilkan daftar tagihan sudah dibayar
     */
    public function tagihanSudahDibayar()
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->firstOrFail();
        
        $tahunAjaranAktif = TahunAjaran::where('status', 'Aktif')->first();

        $tagihanLunas = PembayaranSpp::where('siswa_id', $siswa->id_siswa)
            ->where('status', 'Lunas')
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id_tahun_ajaran)
            ->with('tahunAjaran')
            ->orderBy('tgl_bayar', 'desc')
            ->get();

        return view('siswa.tagihanSudahDibayar', compact('siswa', 'tagihanLunas', 'tahunAjaranAktif'));
    }

    /**
     * Detail tagihan belum dibayar
     */
    public function detailTagihan($id)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->firstOrFail();
        
        $tagihan = PembayaranSpp::where('id_pembayaran', $id)
            ->where('siswa_id', $siswa->id_siswa)
            ->with('tahunAjaran')
            ->firstOrFail();

        if ($tagihan->status === 'Lunas') {
            return redirect()->route('siswa.detail_tagihan_sudah_dibayar', $id);
        }

        return view('siswa.detailTagihan', compact('siswa', 'tagihan'));
    }

    /**
     * Detail tagihan sudah dibayar
     */
    public function detailTagihanSudahDibayar($id)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->with('kelas')->firstOrFail();
        
        $tagihan = PembayaranSpp::where('id_pembayaran', $id)
            ->where('siswa_id', $siswa->id_siswa)
            ->where('status', 'Lunas')
            ->with('tahunAjaran')
            ->firstOrFail();

        return view('siswa.detailTagihanSudahDibayar', compact('siswa', 'tagihan'));
    }

    /**
     * Create payment via Midtrans
     */
    public function createPayment(Request $request, $id)
    {
       $validated = $request->validate([
    'metode_pembayaran' => 'required|in:bank_transfer,gopay,shopeepay',
    // Jika metode transfer, user harus pilih bank (bca, bni, bri)
    'bank' => 'required_if:metode_pembayaran,bank_transfer|in:bca,bni,bri,permata', 
]);

        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        
        $tagihan = PembayaranSpp::where('id_pembayaran', $id)
            ->where('siswa_id', $siswa->id_siswa)
            ->where('status', 'Belum Lunas')
            ->firstOrFail();

        try {
            DB::beginTransaction();

            // Generate unique order ID
            $orderId = 'SPP-' . $tagihan->id_pembayaran . '-' . time();

            $customerDetails = [
                'first_name' => $siswa->nama_lengkap,
                'email' => $siswa->email ?? $user->email,
                'phone' => $siswa->no_telepon ?? '08123456789',
            ];

            $bulanText = [
                1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
            ];

            $itemDetails = [[
                'id' => $tagihan->id_pembayaran,
                'price' => (int) $tagihan->jumlah_bayar,
                'quantity' => 1,
                'name' => $tagihan->nama_tagihan . ' - ' . $bulanText[$tagihan->bulan] . ' ' . $tagihan->tahun,
            ]];

            // Create payment based on method
            if ($validated['metode_pembayaran'] === 'bank_transfer') {
                $response = $this->midtransService->createVirtualAccount(
                    $orderId,
                    $tagihan->jumlah_bayar,
                    $customerDetails,
                    $itemDetails
                    $request->bank
                );
                
                $paymentType = 'Transfer Bank ' . strtoupper($request->bank);
                $nomorVa = $response->va_numbers[0]->va_number ?? null;
            } else {
                $response = $this->midtransService->createEwalletPayment(
                    $orderId,
                    $tagihan->jumlah_bayar,
                    $customerDetails,
                    $itemDetails,
                    $validated['metode_pembayaran']
                );
                
                $paymentType = ucfirst($validated['metode_pembayaran']);
                $nomorVa = null;
            }

            // Update tagihan dengan informasi Midtrans
            $tagihan->update([
                'midtrans_order_id' => $orderId,
                'midtrans_transaction_id' => $response->transaction_id,
                'midtrans_payment_type' => $validated['metode_pembayaran'],
                'midtrans_transaction_status' => $response->transaction_status,
                'midtrans_response' => json_encode($response),
                'metode_pembayaran' => $paymentType,
                'nomor_va' => $nomorVa,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil dibuat',
                'data' => [
                    'order_id' => $orderId,
                    'transaction_status' => $response->transaction_status,
                    'payment_type' => $validated['metode_pembayaran'],
                    'va_number' => $nomorVa,
                    'qr_code' => $response->actions[0]->url ?? null, // For e-wallet
                    'deeplink' => $response->actions[1]->url ?? null, // For e-wallet app
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans payment creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus($id)
    {
        $user = Auth::user();
        $siswa = Siswa::where('user_id', $user->id)->firstOrFail();
        
        $tagihan = PembayaranSpp::where('id_pembayaran', $id)
            ->where('siswa_id', $siswa->id_siswa)
            ->firstOrFail();

        if (!$tagihan->midtrans_order_id) {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran belum dibuat'
            ], 400);
        }

        try {
            $status = $this->midtransService->checkStatus($tagihan->midtrans_order_id);
            
            // Update status tagihan berdasarkan response Midtrans
            $this->updatePaymentStatus($tagihan, $status);

            return response()->json([
                'success' => true,
                'data' => [
                    'transaction_status' => $status->transaction_status,
                    'payment_type' => $status->payment_type,
                    'status_tagihan' => $tagihan->fresh()->status,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans status check failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Midtrans webhook/notification handler
     */
    public function handleNotification(Request $request)
    {
        try {
            $notification = $this->midtransService->handleNotification();
            
            $tagihan = PembayaranSpp::where('midtrans_order_id', $notification['order_id'])->first();

            if (!$tagihan) {
                return response()->json(['message' => 'Order not found'], 404);
            }

            $this->updatePaymentStatus($tagihan, $notification['notification']);

            return response()->json(['message' => 'Notification handled']);

        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }
    }

    /**
     * Update payment status based on Midtrans response
     */
    private function updatePaymentStatus($tagihan, $midtransData)
    {
        $transactionStatus = $midtransData->transaction_status;
        $fraudStatus = $midtransData->fraud_status ?? null;

        $tagihan->midtrans_transaction_status = $transactionStatus;
        $tagihan->midtrans_response = json_encode($midtransData);

        if ($transactionStatus == 'capture') {
            if ($fraudStatus == 'accept') {
                $tagihan->status = 'Lunas';
                $tagihan->tgl_bayar = now();
                $tagihan->midtrans_paid_at = now();
            }
        } elseif ($transactionStatus == 'settlement') {
            $tagihan->status = 'Lunas';
            $tagihan->tgl_bayar = now();
            $tagihan->midtrans_paid_at = now();
        } elseif ($transactionStatus == 'pending') {
            // Pembayaran masih pending
            $tagihan->status = 'Belum Lunas';
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
            // Pembayaran ditolak/expired/dibatalkan
            $tagihan->status = 'Belum Lunas';
        }

        $tagihan->save();
    }

    /**
     * Callback URL untuk e-wallet
     */
    public function callback(Request $request)
    {
        return redirect()->route('siswa.tagihan.index')->with('success', 'Silakan cek status pembayaran Anda');
    }
}

