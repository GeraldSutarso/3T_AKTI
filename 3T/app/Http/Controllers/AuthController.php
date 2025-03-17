<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Auth;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

class AuthController extends Controller
{
    /**
     * Display the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Create resources/views/auth/login.blade.php
    }

    /**
     * Process the login form:
     * - Validate the student_id and device_identifier.
     * - Check if the device is already verified within 6 months.
     * - If verified, log the user in immediately.
     * - Otherwise, generate and send a verification code.
     */
    public function sendVerificationCode(Request $request)
    {
        $request->validate([
            'student_id'        => 'required|exists:users,student_id',
            'device_identifier' => 'required',
        ]);

        // Retrieve the user by student_id.
        $user = User::where('student_id', $request->student_id)->first();

        // ✅ Restrict login only to users from group_id 102
        if ($user->group_id != 102) {
            return redirect()->back()->withErrors(['student_id' => 'You are not authorized to access this application.']);
        }

        // Check if this device was verified in the past 6 months.
        $device = UserDevice::where('user_id', $user->id)
                    ->where('device_identifier', $request->device_identifier)
                    ->first();
        $now = Carbon::now();

        if ($device && $device->last_verified_at && $device->last_verified_at->diffInMonths($now) < 6) {
            // Device is already verified within 6 months.
            Auth::login($user, true);
            return redirect()->route('dashboard');
        }

        // Device not verified or new device: generate a verification code.
        $code = random_int(100000, 999999);

        // Store the verification details in the session.
        session([
            'login_verification_code' => $code,
            'login_user_id'           => $user->id,
            'device_identifier'       => $request->device_identifier,
        ]);

        // Define the predefined email address (or load from config).
        $predefinedEmail = 'testg2984@gmail.com';

        // Send the verification code via email.
        Mail::to($predefinedEmail)->send(new VerificationCodeMail($code));

        // Redirect to the verification form with a message.
        return redirect()->route('verify.form')->with('message', 'Verification code sent.');
    }


    /**
     * Display the verification form where the user can enter the code.
     */
    public function showVerificationForm()
    {
        return view('auth.verify'); // Create resources/views/auth/verify.blade.php
    }

    /**
     * Verify the entered code:
     * - If correct, log the user in and update/create the device record with the current timestamp.
     * - Otherwise, show an error.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required',
        ]);

        if ($request->code == session('login_verification_code')) {
            $user = User::find(session('login_user_id'));

            // ✅ Double-check user group before allowing login
            if ($user->group_id != 102) {
                session()->forget(['login_verification_code', 'login_user_id', 'device_identifier']);
                return redirect()->route('login')->withErrors(['student_id' => 'Unauthorized access.']);
            }

            // Log the user in with persistent "remember me" enabled.
            Auth::login($user, true);

            // Update or create the device record with the current timestamp.
            $deviceIdentifier = session('device_identifier');
            UserDevice::updateOrCreate(
                ['user_id' => $user->id, 'device_identifier' => $deviceIdentifier],
                ['last_verified_at' => Carbon::now()]
            );

            // Clear the verification session data.
            session()->forget(['login_verification_code', 'login_user_id', 'device_identifier']);

            return redirect()->route('dashboard');
        } else {
            return redirect()->back()->withErrors(['code' => 'Invalid verification code.']);
        }
    }
    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect()->route('login.form');
    }

}
