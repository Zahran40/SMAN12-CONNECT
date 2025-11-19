<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show form to request OTP
     */
    public function showRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.exists' => 'Email tidak terdaftar',
        ]);

        try {
            // Generate 6-digit OTP
            $otp = random_int(100000, 999999);
            
            // Delete old OTP tokens for this email
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            
            // Save new OTP token
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'otp' => $otp,
                'created_at' => Carbon::now(),
                'expires_at' => Carbon::now()->addMinutes(10), // OTP valid for 10 minutes
            ]);

            // Send OTP via email
            Mail::raw("Kode OTP untuk reset password Anda adalah: $otp\n\nKode ini berlaku selama 10 menit.", function($message) use ($request) {
                $message->to($request->email)
                    ->subject('Reset Password - SMAN 12 Connect');
            });

            return redirect()->route('password.reset.form')->with('email', $request->email)->with('success', 'Kode OTP telah dikirim ke email Anda');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengirim OTP: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Show form to reset password with OTP
     */
    public function showResetForm()
    {
        if (!session('email')) {
            return redirect()->route('password.request')->with('error', 'Silakan request OTP terlebih dahulu');
        }

        return view('auth.reset-password');
    }

    /**
     * Reset password with OTP verification
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.exists' => 'Email tidak terdaftar',
            'otp.required' => 'Kode OTP wajib diisi',
            'otp.size' => 'Kode OTP harus 6 digit',
            'password.required' => 'Password baru wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            // Check OTP
            $token = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('otp', $request->otp)
                ->first();

            if (!$token) {
                return back()->with('error', 'Kode OTP tidak valid')->withInput();
            }

            // Check if OTP expired
            if (Carbon::parse($token->expires_at)->isPast()) {
                DB::table('password_reset_tokens')->where('email', $request->email)->delete();
                return back()->with('error', 'Kode OTP sudah kadaluarsa. Silakan request kode baru')->withInput();
            }

            // Update password
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete OTP token
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->route('login')->with('success', 'Password berhasil direset. Silakan login dengan password baru');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal reset password: ' . $e->getMessage())->withInput();
        }
    }
}
