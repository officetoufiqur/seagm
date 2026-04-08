<?php

namespace App\Http\Controllers\Profile;

use App\Helpers\FileUpload;
use App\Http\Controllers\Controller;
use App\Models\BillingAddress;
use App\Trait\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    use ApiResponse;

    public function show()
    {
        $user = Auth::user();

        if (! $user) {
            return $this->errorResponse('User not found', 404);
        }

        return $this->successResponse($user, 'User profile retrieved successfully');
    }

    public function imageUpdate(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $user = Auth::user();

        if (! $user) {
            return $this->errorResponse('User not found', 404);
        }

        $path = $user->image;
        if ($request->hasFile('image')) {
            $path = FileUpload::updateFile($request->file('image'), 'profile_images', $user->image);
        }

        $user->image = $path;
        $user->save();

        return $this->successResponse($user, 'Profile image updated successfully');
    }

    public function usernameUpdate(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username,'.Auth::id(),
        ]);

        $user = Auth::user();

        if (! $user) {
            return $this->errorResponse('User not found', 404);
        }

        $user->username = $request->username;
        $user->save();

        return $this->successResponse($user, 'Username updated successfully');
    }

    public function changePasswordOtp(Request $request)
    {
        $request->validate([
            'email' => 'required_without:mobile|email',
            'mobile' => 'required_without:email',
        ]);

        $user = Auth::user();

        if (! $user) {
            return $this->errorResponse('User not found', 404);
        }

        if ($request->email) {
            $user->email_verified_code = '123456';
            $user->save();

            return $this->successResponse(null, 'OTP sent to email successfully');
        } else {
            $user->mobile_verified_code = '123456';
            $user->save();

            return $this->successResponse(null, 'OTP sent to mobile successfully');
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'otp' => 'required',
            'email' => 'required_without:mobile|email',
            'mobile' => 'required_without:email',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:20',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d&@#]{8,20}$/',
            ],
        ]);

        $user = Auth::user();

        if (! $user) {
            return $this->errorResponse('User not found', 404);
        }

        if ($request->email) {
            if ($user->email_verified_code !== $request->otp) {
                return $this->errorResponse('Invalid OTP', 400);
            }

            $user->update([
                'email_verified_code' => null,
                'password' => Hash::make($request->password),
            ]);
        } elseif ($request->mobile) {

            if ($user->mobile_verified_code !== $request->otp) {
                return $this->errorResponse('Invalid OTP', 400);
            }

            $user->update([
                'mobile_verified_code' => null,
                'password' => Hash::make($request->password),
            ]);
        }

        return $this->successResponse($user, 'Password updated successfully');
    }

    public function getBillingAddress()
    {
        $user = Auth::user();

        if (! $user) {
            return $this->errorResponse('User not found', 404);
        }

        $billingAddress = BillingAddress::where('user_id', $user->id)->first();

        return $this->successResponse($billingAddress, 'Billing address retrieved successfully');
    }

    public function addBillingAddress(Request $request)
    {
        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'address' => 'required',
            'zip_code' => 'required',
            'city' => 'required',
            'state' => 'required',
            'country' => 'required',
        ]);

        $user = Auth::user();

        if (! $user) {
            return $this->errorResponse('User not found', 404);
        }

        $billingAddress = BillingAddress::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'fname' => $request->fname,
                'lname' => $request->lname,
                'address' => $request->address,
                'zip_code' => $request->zip_code,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country,
            ]);

        $billingAddress->save();

        return $this->successResponse($billingAddress, 'Billing address added successfully');
    }

    public function deleteAccount()
    {
        $user = Auth::user();

        if (! $user) {
            return $this->errorResponse('User not found', 404);
        }

        $user->delete();

        return $this->successResponse(null, 'Account deleted successfully');
    }
}
