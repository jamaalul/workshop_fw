<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();

            $finduser = User::where('id_google', $user->id)
                ->orWhere('email', $user->email)
                ->first();

            if ($finduser) {
                // Update id_google if not set
                if (! $finduser->id_google) {
                    $finduser->update(['id_google' => $user->id]);
                }

                Auth::login($finduser);
            } else {
                $newUser = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'id_google' => $user->id,
                    'password' => encrypt('123456dummy'), // Placeholder password
                ]);

                Auth::login($newUser);
            }

            // Generate OTP and trigger flow
            $authUser = Auth::user();
            Auth::logout();

            $otp = rand(100000, 999999);
            $authUser->update(['otp' => $otp]);

            // Import Mail and OtpMail at the top
            \Illuminate\Support\Facades\Mail::to($authUser->email)->send(new \App\Mail\OtpMail($otp));

            session(['otp_login_user_id' => $authUser->id]);

            return redirect()->route('otp.form');

        } catch (Exception $e) {
            return redirect('login')->with('error', 'Something went wrong with Google Login.');
        }
    }
}
