<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // ================= 1. லாகின் ஃபங்க்ஷன் (ஏற்கனவே உங்களிடம் உள்ளது) =================
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token, // 💡 Flutter-ல் 'token' என்று வாங்குவதால் இதையும் சேர்த்துள்ளோம்
            'access_token' => $token,
            'user' => $user,
        ]);
    }

    // ================= 💡 2. புதிய ரெஜிஸ்டர் ஃபங்க்ஷன் (UPDATED) =================
    public function register(Request $request)
    {
        // எர்ரர் வந்தால் HTML பக்கம் போகாமல் தடுக்க லாரவெல்லுக்கு கட்டாயப்படுத்துகிறோம்
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // வேலிடேஷன் எர்ரரை சுத்தமான JSON ஆக திருப்பி அனுப்புகிறோம்
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        }

        // புதிய யூசரை டேட்டாபேஸில் உருவாக்குதல்
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user
        ], 201);
    }

    // ================= 💡 3. புதிய ஃபர்காட் பாஸ்வேர்ட் ஃபங்க்ஷன் (NEW) =================
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        // யூசர் இல்லை என்றால் எர்ரர் மெசேஜ்
        if (!$user) {
            return response()->json(['message' => 'User with this email does not exist.'], 404);
        }

        // 💡 ப்ரொபஷனல் ஆப்பில் இங்கே ஈமெயில் லிங்க் லாஜிக் வரும். 
        // இப்போதைக்கு டெஸ்டிங்கிற்காக எளிய வெற்றிகரமான ரெஸ்பான்ஸ் மட்டும் அனுப்புகிறோம்.
        return response()->json([
            'message' => 'Password reset link sent to your email successfully!'
        ], 200);
    }
}