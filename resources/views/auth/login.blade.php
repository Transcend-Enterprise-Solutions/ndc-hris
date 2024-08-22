<x-authentication-layout>
    <style>
        @keyframes slideInLeft {
            from {
                transform: translateX(-100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .animate-slide-in-left {
            opacity: 0;
            transform: translateX(-100%);
            animation: slideInLeft 0.5s ease-out forwards;
        }

        .animate-slide-in-left-delay-1 {
            opacity: 0;
            transform: translateX(-100%);
            animation: slideInLeft 0.5s ease-out 0.05s forwards;
        }

        .animate-slide-in-left-delay-2 {
            opacity: 0;
            transform: translateX(-100%);
            animation: slideInLeft 0.4s ease-out 0.1s forwards;
        }
    </style>

    <!-- Welcome Message -->
    <h1 class="text-3xl text-slate-800 font-bold mb-6 animate-slide-in-left">
        {{ __('Welcome back!') }}
    </h1>

    @if (session('status'))
        <!-- Status Message -->
        <div class="mb-4 font-medium text-sm text-green-600 animate-slide-in-left-delay-1">
            {{ session('status') }}
        </div>
    @endif

    <!-- Form -->
    <form method="POST" wire:submit.prevent='login' class="animate-slide-in-left-delay-2">
        @csrf
        <div class="space-y-4 w-full">
            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            </div>
            <div class="relative w-full" x-data="{ show: false }">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input
                    id="password"
                    x-bind:type="show ? 'text' : 'password'"
                    name="password"
                    required
                    autocomplete="current-password"
                    wire:model.live="password"
                    class="w-full h-12 px-4 py-2 text-black border rounded-lg appearance-none bg-chalk border-zinc-300 placeholder-zinc-300 focus:border-zinc-300 focus:outline-none focus:ring-zinc-300 sm:text-sm"
                />
                <button type="button" class="absolute top-1/2 right-0 px-3 flex items-center text-sm leading-5" @click="show = !show">
                    <i x-bind:class="show ? 'bi bi-eye' : 'bi bi-eye-slash'" class="text-lg text-gray-500" ></i>
                </button>
                @error('password')
                    <span class="text-red-500 text-sm mt-1">This field is required!</span>
                @enderror
            </div>
        </div>
        <div class="flex items-center justify-between mt-6">
            @if (Route::has('password.request'))
                <div class="mr-1">
                    <a class="text-sm underline hover:no-underline" href="{{ route('password.request') }}">
                        {{ __('Forgot Password?') }}
                    </a>
                </div>
            @endif
            <x-button class="ml-3">
                {{ __('Sign in') }}
            </x-button>
        </div>
    </form>

    <x-validation-errors class="mt-4" />

    @error('login')
        <span class="text-red-500 animate-slide-in-left-delay-2">
            {{ $message }}
        </span>
    @enderror
</x-authentication-layout>
