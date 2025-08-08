<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-gray-900">
        <div>
            <a href="/">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ config('app.name', 'Laravel') }}</h1>
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-800 shadow-md overflow-hidden sm:rounded-lg">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            </div>

            <form method="POST" action="{{ route('two-factor.store') }}">
                @csrf

                <div x-data="twoFactorAuth()">
                    <div class="mt-4" x-show="! recovery">
                        <label class="block font-medium text-sm text-gray-700 dark:text-gray-300 mb-3">{{ __('Code') }}</label>
                        
                        <!-- 6 separate input boxes -->
                        <div class="flex space-x-2 justify-center">
                            <input 
                                x-ref="digit0"
                                x-model="code[0]"
                                @input="handleInput(0, $event)"
                                @keydown="handleKeydown(0, $event)"
                                @paste="handlePaste(0, $event)"
                                type="tel" 
                                inputmode="numeric" 
                                pattern="[0-9]*"
                                autocomplete="one-time-code"
                                maxlength="1"
                                class="w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:outline-none transition-colors"
                                :class="{ 'border-indigo-500 dark:border-indigo-400': code[0] }"
                                autocomplete="off"
                                autofocus
                            />
                            <input 
                                x-ref="digit1"
                                x-model="code[1]"
                                @input="handleInput(1, $event)"
                                @keydown="handleKeydown(1, $event)"
                                @paste="handlePaste(1, $event)"
                                type="tel" 
                                inputmode="numeric" 
                                pattern="[0-9]*"
                                autocomplete="one-time-code"
                                maxlength="1"
                                class="w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:outline-none transition-colors"
                                :class="{ 'border-indigo-500 dark:border-indigo-400': code[1] }"
                                autocomplete="off"
                            />
                            <input 
                                x-ref="digit2"
                                x-model="code[2]"
                                @input="handleInput(2, $event)"
                                @keydown="handleKeydown(2, $event)"
                                @paste="handlePaste(2, $event)"
                                type="tel" 
                                inputmode="numeric" 
                                pattern="[0-9]*"
                                autocomplete="one-time-code"
                                maxlength="1"
                                class="w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:outline-none transition-colors"
                                :class="{ 'border-indigo-500 dark:border-indigo-400': code[2] }"
                                autocomplete="off"
                            />
                            <span class="text-lg h-12 w-12 flex items-center justify-center font-semibold text-gray-600 dark:text-gray-400">-</span>
                            <input 
                                x-ref="digit3"
                                x-model="code[3]"
                                @input="handleInput(3, $event)"
                                @keydown="handleKeydown(3, $event)"
                                @paste="handlePaste(3, $event)"
                                type="tel" 
                                inputmode="numeric" 
                                pattern="[0-9]*"
                                autocomplete="one-time-code"
                                maxlength="1"
                                class="w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:outline-none transition-colors"
                                :class="{ 'border-indigo-500 dark:border-indigo-400': code[3] }"
                                autocomplete="off"
                            />
                            <input 
                                x-ref="digit4"
                                x-model="code[4]"
                                @input="handleInput(4, $event)"
                                @keydown="handleKeydown(4, $event)"
                                @paste="handlePaste(4, $event)"
                                type="tel" 
                                inputmode="numeric" 
                                pattern="[0-9]*"
                                autocomplete="one-time-code"
                                maxlength="1"
                                class="w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:outline-none transition-colors"
                                :class="{ 'border-indigo-500 dark:border-indigo-400': code[4] }"
                                autocomplete="off"
                            />
                            <input 
                                x-ref="digit5"
                                x-model="code[5]"
                                @input="handleInput(5, $event)"
                                @keydown="handleKeydown(5, $event)"
                                @paste="handlePaste(5, $event)"
                                type="tel" 
                                inputmode="numeric" 
                                pattern="[0-9]*"
                                autocomplete="one-time-code"
                                maxlength="1"
                                class="w-12 h-12 text-center text-lg font-semibold border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white rounded-lg focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 focus:outline-none transition-colors"
                                :class="{ 'border-indigo-500 dark:border-indigo-400': code[5] }"
                                autocomplete="off"
                            />
                        </div>
                        
                        <!-- Hidden input for form submission -->
                        <input type="hidden" name="code" x-ref="hiddenCode" :value="getFullCode()" />
                        
                        @error('code_error')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2 text-center">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-4" x-show="recovery">
                        <label for="recovery_code" class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Recovery Code') }}</label>
                        <input id="recovery_code" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
                        @error('recovery_code')
                            <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <button type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 underline cursor-pointer"
                                            x-show="! recovery"
                                            x-on:click="
                                                recovery = true;
                                                $nextTick(() => { $refs.recovery_code.focus() })
                                            ">
                            {{ __('Use a recovery code') }}
                        </button>

                        <button type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 underline cursor-pointer"
                                            x-show="recovery"
                                            x-on:click="
                                                recovery = false;
                                                $nextTick(() => { $refs.digit0.focus() })
                                            ">
                            {{ __('Use an authentication code') }}
                        </button>

                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 ml-4">
                            {{ __('Log in') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
       // Al pulsar enter en la última casilla enviar el formulario
       document.addEventListener('keydown', function(event) {
           if (event.key === 'Enter') {
               event.preventDefault();
               document.querySelector('form').submit();
           }
       });

        function initializeTwoFactorAuth() {
            // Si Alpine está disponible, registra el componente
            if (window.Alpine) {
                Alpine.data('twoFactorAuth', function() {
                    return {
                        recovery: false,
                        code: ['', '', '', '', '', ''],
                        getFullCode() {
                            return this.code.join('');
                        },
                        handleInput(index, event) {
                            const digits = (event.target.value || '').replace(/\D/g, '');
                            if (digits.length === 0) {
                                this.code[index] = '';
                                event.target.value = '';
                                this.$refs.hiddenCode.value = this.getFullCode();
                                return;
                            }
                            const d = digits[0];
                            this.code[index] = d;
                            event.target.value = d;
                            if (index < 5) {
                                this.$nextTick(() => this.$refs['digit' + (index + 1)].focus());
                            }
                            this.$refs.hiddenCode.value = this.getFullCode();
                        },
                        handleKeydown(index, event) {
                            const allowedKeys = ['0','1','2','3','4','5','6','7','8','9','Backspace','Delete','Tab','ArrowLeft','ArrowRight'];
                            
                            if (!allowedKeys.includes(event.key)) {
                                event.preventDefault();
                                return;
                            }
                            
                            if (event.key === 'Backspace') {
                                if (!this.code[index] && index > 0) {
                                    this.$nextTick(() => {
                                        this.$refs['digit' + (index - 1)].focus();
                                    });
                                } else if (this.code[index] && index > 0) {
                                    this.code[index] = '';
                                    event.target.value = '';
                                    this.$refs.hiddenCode.value = this.getFullCode();
                                    this.$nextTick(() => {
                                        this.$refs['digit' + (index - 1)].focus();
                                    });
                                    event.preventDefault();
                                }
                            }
                        },
                        handlePaste(index, event) {
                            event.preventDefault();
                            const paste = (event.clipboardData || window.clipboardData).getData('text');
                            const digits = paste.replace(/[^0-9]/g, '').slice(0, 6);
                            
                            for (let i = 0; i < digits.length && (index + i) < 6; i++) {
                                this.code[index + i] = digits[i];
                                this.$refs['digit' + (index + i)].value = digits[i];
                            }
                            
                            const nextIndex = Math.min(index + digits.length, 5);
                            this.$refs['digit' + nextIndex].focus();
                            this.$refs.hiddenCode.value = this.getFullCode();
                        }
                    };
                });
            } else {
                // Si Alpine no está disponible, espera a que el DOM esté listo
                document.addEventListener('DOMContentLoaded', function() {
                    initializeTwoFactorAuth();
                });
            }
        }
    
        // Intenta inicializar inmediatamente
        initializeTwoFactorAuth();
    
        // También escucha el evento alpine:init por si Alpine se carga después
        document.addEventListener('alpine:init', initializeTwoFactorAuth);
    </script>
</body>
</html>