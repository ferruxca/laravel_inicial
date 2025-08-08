<?php

use Livewire\Volt\Component;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

new class extends Component
{
    public bool $showingQrCode = false;
    public bool $showingConfirmation = false;
    public bool $showingRecoveryCodes = false;
    public string $code = '';
    public array $recoveryCodes = [];

    /**
     * Enable two-factor authentication for the user.
     */
    public function enableTwoFactorAuthentication(): void
    {
        $this->resetErrorBag();

        $user = auth()->user();
        $google2fa = new Google2FA();
        
        $user->forceFill([
            'two_factor_secret' => encrypt($google2fa->generateSecretKey()),
        ])->save();

        $this->showingQrCode = true;
    }

    /**
     * Confirm two-factor authentication for the user.
     */
    public function confirmTwoFactorAuthentication(): void
    {
        $this->resetErrorBag();

        if (empty($this->code)) {
            throw ValidationException::withMessages([
                'code' => [__('The code field is required.')],
            ]);
        }

        $user = auth()->user();
        $google2fa = new Google2FA();
        
        $valid = $google2fa->verifyKey(
            decrypt($user->two_factor_secret), 
            $this->code
        );

        if (!$valid) {
            throw ValidationException::withMessages([
                'code' => [__('The provided two factor authentication code was invalid.')],
            ]);
        }

        $user->forceFill([
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => encrypt(Collection::times(8, function () {
                return Str::random(10).'-'.Str::random(10);
            })->all()),
        ])->save();

        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = true;
        $this->recoveryCodes = decrypt($user->two_factor_recovery_codes);
        $this->code = '';

        session()->flash('status', __('Two factor authentication has been enabled.'));
    }

    /**
     * Display the user's recovery codes.
     */
    public function showRecoveryCodes(): void
    {
        $this->showingRecoveryCodes = true;
        $this->recoveryCodes = decrypt(auth()->user()->two_factor_recovery_codes);
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function regenerateRecoveryCodes(): void
    {
        $user = auth()->user();
        
        $user->forceFill([
            'two_factor_recovery_codes' => encrypt(Collection::times(8, function () {
                return Str::random(10).'-'.Str::random(10);
            })->all()),
        ])->save();

        $this->showRecoveryCodes();

        session()->flash('status', __('New recovery codes have been generated.'));
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disableTwoFactorAuthentication(): void
    {
        $user = auth()->user();
        
        $user->forceFill([
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null,
        ])->save();

        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = false;

        session()->flash('status', __('Two factor authentication has been disabled.'));
    }

    /**
     * Get the QR code SVG for the user's two-factor authentication setup.
     */
    public function getQrCodeSvg(): string
    {
        $user = auth()->user();
        $google2fa = new Google2FA();
        
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            decrypt($user->two_factor_secret)
        );

        $writer = new \BaconQrCode\Writer(
            new \BaconQrCode\Renderer\ImageRenderer(
                new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200),
                new \BaconQrCode\Renderer\Image\SvgImageBackEnd()
            )
        );

        return $writer->writeString($qrCodeUrl);
    }

    public function mount(): void
    {
        if (auth()->user()->two_factor_secret && !auth()->user()->two_factor_confirmed_at) {
            $this->showingConfirmation = true;
        }
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Two-Factor Authentication')" :subheading="__('Add additional security to your account using two-factor authentication.')">

    <div class="space-y-6">
        @if (session('status'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('status') }}
            </div>
        @endif

        @if (! auth()->user()->hasEnabledTwoFactorAuthentication())
            <!-- Two Factor Authentication is not enabled -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('You have not enabled two factor authentication.') }}
                </h3>

                <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                    <p>
                        {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
                    </p>
                </div>

                <div class="mt-5">
                    @if (! $showingQrCode)
                        <button wire:click="enableTwoFactorAuthentication" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            {{ __('Enable') }}
                        </button>
                    @else
                        @if ($showingConfirmation)
                            <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                                <p class="font-semibold">
                                    {{ __('To finish enabling two factor authentication, scan the following QR code using your phone\'s authenticator application or enter the setup key and provide the generated OTP code.') }}
                                </p>
                            </div>

                            <div class="mt-4 p-2 inline-block bg-white">
                                {!! $this->getQrCodeSvg() !!}
                            </div>

                            <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                                <p class="font-semibold">
                                    {{ __('Setup Key') }}: {{ decrypt(auth()->user()->two_factor_secret) }}
                                </p>
                            </div>

                            <div class="mt-4">
                                <div>
                                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Code') }}</label>
                                    <input wire:model="code" type="text" id="code" inputmode="numeric" autofocus autocomplete="one-time-code" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                                    @error('code')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-5 flex items-center gap-4">
                                <button wire:click="confirmTwoFactorAuthentication" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Confirm') }}
                                </button>

                                <button wire:click="disableTwoFactorAuthentication" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                    {{ __('Cancel') }}
                                </button>
                            </div>
                        @else
                            <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                                <p class="font-semibold">
                                    {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                                </p>
                            </div>

                            <div class="mt-4 p-2 inline-block bg-white">
                                {!! $this->getQrCodeSvg() !!}
                            </div>

                            <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                                <p class="font-semibold">
                                    {{ __('Setup Key') }}: {{ decrypt(auth()->user()->two_factor_secret) }}
                                </p>
                            </div>

                            <div class="mt-4">
                                <div>
                                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Code') }}</label>
                                    <input wire:model="code" type="text" id="code" inputmode="numeric" autofocus autocomplete="one-time-code" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" />
                                    @error('code')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-5">
                                <button wire:click="confirmTwoFactorAuthentication" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    {{ __('Confirm') }}
                                </button>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        @else
            <!-- Two Factor Authentication is enabled -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('You have enabled two factor authentication.') }}
                </h3>

                <div class="mt-3 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                    <p>
                        {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application or enter the setup key.') }}
                    </p>
                </div>

                @if ($showingQrCode)
                    <div class="mt-4 p-2 inline-block bg-white">
                        {!! $this->getQrCodeSvg() !!}
                    </div>

                    <div class="mt-4 max-w-xl text-sm text-gray-600 dark:text-gray-400">
                        <p class="font-semibold">
                            {{ __('Setup Key') }}: {{ decrypt(auth()->user()->two_factor_secret) }}
                        </p>
                    </div>
                @endif

                <div class="mt-5">
                    @if (! $showingQrCode)
                        <button wire:click="showingQrCode = true" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Show QR Code') }}
                        </button>
                    @else
                        <button wire:click="showingQrCode = false" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Hide QR Code') }}
                        </button>
                    @endif
                </div>
            </div>

            <!-- Recovery Codes -->
            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Recovery Codes') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                            @if ($showingRecoveryCodes)
                                <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                                    <p class="font-semibold">
                                        {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                                    </p>
                                </div>

                                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 dark:bg-gray-900 rounded-lg">
                                    @foreach ($recoveryCodes as $code)
                                        <div>{{ $code }}</div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex items-center mt-5">
                                @if (! $showingRecoveryCodes)
                                    <button wire:click="showRecoveryCodes" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        {{ __('Show Recovery Codes') }}
                                    </button>
                                @else
                                    <button wire:click="regenerateRecoveryCodes" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                                        {{ __('Regenerate Recovery Codes') }}
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disable Two Factor Authentication -->
            <div class="mt-10 sm:mt-0">
                <div class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Disable Two Factor Authentication') }}</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                {{ __('When two factor authentication is disabled, you will no longer be prompted for a token during authentication.') }}
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
                            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-400">
                                <p>
                                    {{ __('When two factor authentication is disabled, you will no longer be prompted for a token during authentication.') }}
                                </p>
                            </div>

                            <div class="mt-5">
                                <button 
                                    wire:click="disableTwoFactorAuthentication" 
                                    wire:confirm="{{ __('Are you sure you want to disable two factor authentication?') }}"
                                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    {{ __('Disable') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    </x-settings.layout>
</section>
