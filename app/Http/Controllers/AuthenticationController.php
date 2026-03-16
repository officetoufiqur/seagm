<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
    use ApiResponse;

    public function sendEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $otp = '123456';

        $user = User::create(
            ['email' => $request->email],
            ['email_verified_code' => $otp]
        );

        // send email code

        return $this->successResponse(
            $user,
            'OTP sent to email'
        );
    }

    // Verify Email OTP
    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        $user = User::where('email', $request->email)
            ->where('email_verified_code', $request->otp)
            ->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP',
            ]);
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verified_code' => null,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Email verified successfully',
        ]);
    }

    // Send Mobile OTP
    public function sendMobileOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
        ]);

        $otp = '123456';

        $user = User::create(
            ['mobile' => $request->mobile],
            ['mobile_verified_code' => $otp]
        );

        // Here you integrate SMS / WhatsApp API
        // Example: Twilio, Vonage, WhatsApp API

        return $this->successResponse(
            $user,
            'OTP sent to mobile'
        );
    }

    // Verify Mobile OTP
    public function verifyMobileOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'otp' => 'required',
        ]);

        $user = User::where('mobile', $request->mobile)
            ->where('mobile_verified_code', $request->otp)
            ->first();

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP',
            ]);
        }

        $user->update([
            'mobile_verified_at' => now(),
            'mobile_verified_code' => null,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Mobile verified successfully',
        ]);
    }

    public function completeSignup(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'password' => 'required|min:6',
        ]);

        $user = User::find($request->user_id);

        if (! $user) {
            return response()->json([
                'status' => false,
                'message' => 'User not found',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Signup completed successfully',
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required'],
            'password' => ['required'],
        ]);

        $key = Str::lower($request->login).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many login attempts. Try again later.',
            ], 429);
        }

        $field = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'mobile';

        if (! Auth::attempt([$field => $request->login, 'password' => $request->password])) {

            RateLimiter::hit($key, 60);

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        RateLimiter::clear($key);

        $user = Auth::user();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'token' => $token,
                'user' => $user,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return $this->successResponse(
            null,
            'Logout successful',
        );
    }
}
