<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtpController extends Controller
{
    /**
     * Show the OTP input form.
     *
     * @return \Illuminate\View\View
     */
    public function showOtpForm()
    {
        if (!session()->has('otp_login_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.otp');
    }

    /**
     * Verify the OTP code.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userId = session('otp_login_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }

        $user = \App\Models\User::find($userId);

        if ($user && $user->otp === $request->otp) {
            // Clear OTP and log in
            $user->update(['otp' => null]);
            Auth::login($user);
            
            session()->forget('otp_login_user_id');
            
            return redirect()->route('home');
        }

        return back()->withErrors(['otp' => 'The provided OTP is incorrect.']);
    }
}
