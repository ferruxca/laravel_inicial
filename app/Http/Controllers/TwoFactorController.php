<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Validation\ValidationException;

class TwoFactorController extends Controller
{
    /**
     * Show the two factor authentication challenge form.
     */
    public function create()
    {
        return view('auth.two-factor-challenge');
    }

    /**
     * Attempt to authenticate using a two factor authentication code.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'nullable|string',
            'recovery_code' => 'nullable|string',
        ]);

        $user = $request->user();

        if ($code = $request->code) {
            $google2fa = new Google2FA();
            
            if ($google2fa->verifyKey(decrypt($user->two_factor_secret), $code)) {
                $request->session()->put('two_factor_confirmed_at', now()->timestamp);
                return redirect()->intended(route('dashboard'));
            }
        } elseif ($recoveryCode = $request->recovery_code) {
            if (in_array($recoveryCode, $user->recoveryCodes())) {
                $user->replaceRecoveryCode($recoveryCode);
                $request->session()->put('two_factor_confirmed_at', now()->timestamp);
                return redirect()->intended(route('dashboard'));
            }
        }

        throw ValidationException::withMessages([
            'code_error' => [__('The provided two factor authentication code was invalid.')],
        ]);
    }
}
