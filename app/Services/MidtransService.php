<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\CoreApi;
use Midtrans\Notification;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Generate Virtual Account (BCA, BNI, BRI, Permata)
     * Tambahkan parameter $bank (default 'bca')
     */
    public function createVirtualAccount($orderId, $grossAmount, $customerDetails, $itemDetails, $bank = 'bca')
    {
        $params = [
            'payment_type' => 'bank_transfer',
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $grossAmount,
            ],
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
            'bank_transfer' => [
                'bank' => $bank, // <-- Sekarang dinamis mengikuti input controller
                'va_number' => '12345678', // (Opsional) Jika ingin custom VA number
            ],
        ];

        // Custom store name (Opsional, biar muncul nama sekolah di ATM)
        // $params['custom_expiry'] = [
        //     'expiry_duration' => 60,
        //     'unit' => 'minute',
        // ];

        try {
            $response = CoreApi::charge($params);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception('Midtrans VA Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate E-Wallet payment (GoPay, ShopeePay, dll)
     */
    public function createEwalletPayment($orderId, $grossAmount, $customerDetails, $itemDetails, $paymentType = 'gopay')
    {
        $params = [
            'payment_type' => $paymentType,
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $grossAmount,
            ],
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
        ];

        // Konfigurasi spesifik E-Wallet
        if ($paymentType === 'gopay') {
            $params['gopay'] = [
                'enable_callback' => true,
                // Pastikan route ini ada di web.php dan bisa diakses public
                'callback_url' => route('siswa.tagihan.callback'), 
            ];
        } elseif ($paymentType === 'shopeepay') {
            $params['shopeepay'] = [
                'callback_url' => route('siswa.tagihan.callback'),
            ];
        }

        try {
            $response = CoreApi::charge($params);
            return $response;
        } catch (\Exception $e) {
            throw new \Exception('Midtrans E-Wallet Error: ' . $e->getMessage());
        }
    }

    // ... (Method checkStatus, cancel, handleNotification SAMA SEPERTI KODEMU, SUDAH BENAR)
    public function checkStatus($orderId)
    {
        try {
            return CoreApi::status($orderId);
        } catch (\Exception $e) {
            throw new \Exception('Midtrans Error: ' . $e->getMessage());
        }
    }

    public function handleNotification()
    {
        try {
            $notification = new Notification();
            
            // Mapping data agar mudah dibaca controller
            return [
                'order_id' => $notification->order_id,
                'transaction_id' => $notification->transaction_id,
                'transaction_status' => $notification->transaction_status,
                'fraud_status' => $notification->fraud_status,
                'payment_type' => $notification->payment_type,
                'notification' => $notification, // Objek asli buat jaga-jaga
            ];
        } catch (\Exception $e) {
            throw new \Exception('Notification Error: ' . $e->getMessage());
        }
    }
}